<?php

namespace App\Http\Controllers;

use App\Models\Invoices;
use App\Models\Transactions;
use App\Models\User;
use App\Models\UserAccounts;
use App\Services\Contracts\Dynamics\FinOps\IFinOpsService;
use App\Services\Implementations\CustomerService;
use Illuminate\Http\Request;
use App\Services\Implementations\Dynamics\FinOps\FinOpsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\In;
use SaintSystems\OData\IODataClient;
use SaintSystems\OData\ODataClient;
use Illuminate\Support\Facades\Hash;
use App\Console\Commands\CreateReport;
use DateTime;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    private IFinOpsService $finOpsService;
    private IODataClient $oDataClient;
    private CustomerService $customerService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(IFinOpsService $finOpsService, IODataClient $oDataClient, CustomerService $customerService)
    {
        $this->finOpsService = $finOpsService;
        $this->oDataClient = $oDataClient;
        $this->customerService = $customerService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $resp = CreateReport::handle();
        $data = $this->customerService->getCustomerDetails();
        $time = Carbon::now()->timezone(config('app.timezone'));
        $timeString = config('app.timezone');
        return view('templates.homepage', ['customer' => $data, 'time' => $time, 'timezone' => $timeString, 'mailStatus' => $resp]);
    }


    public function customer()
    {
        $data = $this->customerService->getCustomerDetails();

        return view('templates.customer')->with('customer', $data);
    }

    public function editCustomer(Request $request) {
        switch($request->input('action')) {
            case 'change_email':
                    User::whereId(auth()->user()->id)->update([
                        'email' => $request->email
                    ]);

                    return back()->with("message",[ 'type' => "success", 'message' => __('Email changed successfully!')] );

                break;
            case 'change_password':

                    if($request->old_password == '' || $request->new_password == '' ) {
                        return back()->with("message",[ 'type' => 'danger', 'message' => __('Please enter you old and new password')] );
                    }

                    if($request->new_password != $request->confirm_password) {
                        return back()->with("message",[ 'type' => 'danger', 'message' => __('Please confirm your new password')] );
                    }

                    if(!Hash::check($request->old_password, auth()->user()->password)){
                        return back()->with("message",[ 'type' => 'danger', 'message' => __('Old Password doesn\'t match!')] );
                    }

                    User::whereId(auth()->user()->id)->update([
                        'password' => Hash::make($request->new_password)
                    ]);

                    return back()->with("message",['type' => "success", 'message' => __("Password changed successfully!")] );

                break;
        }
    }

    public function invoices()
    {
        $unpaid = [];
        $accounts = $this->customerService->getRelatedAccounts();
        $data = $this->finOpsService->getCustomerInvoices($accounts);

        foreach ($data as &$account) {
            $account['unpaid'] = 0;
            foreach ($account['invoices'] as &$invoice) {
                $status = Invoices::where( 'invoice_id', 'invoice')->orderBy('created_at', 'DESC')->first();

                if ($invoice['AmountCur'] > 0)
                    $account['unpaid'] += $invoice['AmountCur'];

                if ($status) {
                    $invoice['lastTransaction'] = $status->transaction()->first()->toArray();
                }
            }
        }

        return view('templates.invoices')->with('accounts', $data);
    }

    public function transactions()
    {
        $transactions = Transactions::where('user_id', Auth::user()->id)->get();

        return view('templates.transactions')->with('transactions', $transactions);
    }

    public function accounts()
    {
        $accounts = $this->customerService->getRelatedAccounts();
        return view('templates.register-customer-accounts')->with('accounts', $accounts);
    }

    public function connectAccounts(Request $request)
    {
        $data = [
            'account_id' => "КУП" . $request->input('account_id'),
            'invoice_id' => $request->input('invoice_id')
        ];
        $response = $this->finOpsService->validateCustomerInvoice($data);
        if ($response['isValid']) {
            $data['customerName'] = $response['customerName'];
            $result = $this->customerService->addCustomerAccounts($data);
            return redirect()->route('accounts');
        } else {
            return redirect()->route('accounts')->with('error', __('The combination customer number/invoice number is not valid. Try again.'));
        }
    }

    public function removeAccount(Request $request)
    {
        $data = $request->all();
        $this->customerService->removeCustomerAccount($data['account_id']);
        return redirect()->route('accounts');
    }

    public function getAllInvoices()
    {
        $accounts = $this->customerService->getRelatedAccounts();

        $result = $this->finOpsService->getAllInvoices($accounts);

        return view('templates.get-all-invoices')->with('accounts', $result);
    }
    public function downloadInvoice(string $invoiceNumber)
    {
        $result = $this->finOpsService->downloadInvoice($invoiceNumber);

        return $result;
    }

}
