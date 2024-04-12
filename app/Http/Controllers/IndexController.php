<?php

namespace App\Http\Controllers;

use App\Services\Contracts\Dynamics\FinOps\IFinOpsService;
use App\Services\Implementations\CustomerService;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Validator;

class IndexController extends Controller
{
    private CustomerService $customerService;

    public function __construct(IFinOpsService $finOpsService, CustomerService $customerService)
    {
        $this->finOpsService = $finOpsService;
        $this->customerService = $customerService;
    }

    /**
     * Homepage index for non logged users
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('index');
    }

    public function payFast()
    {
        return view('fast-pay');
    }

    public function eInvoice()
    {
        return view('e-invoice');
    }

    public function information()
    {
        return view('information');
    }

    public function sendEInvoice(Request $request)
    {
        $validated = Validator::make($request->all(),
        [
            'name' => 'required|max:255',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'accountNumber' => 'required',
            'invoiceNumber' => 'required',
            'directMarketing' => 'required'
        ]);
        

        if($validated->fails()){
            Session::flash('message', __("Please fill all the fields"));
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }

        $data = [
            'account_id' => "КУП" . str_replace("КУП", "", $request->input('accountNumber')),
            'invoice_id' => $request->input('invoiceNumber')
        ];

        $response = $this->finOpsService->validateCustomerInvoice($data);
        if ($response['isValid']) {
            $data['customerName'] = $response['customerName'];

            $response = $this->customerService->emailSubscribeForEInvoice($request->all());

            Session::flash('message', __('You have successfully logged in to receive an e-invoice. Wait for an email confirmation.') );
            Session::flash('alert-class', 'alert-success');

            return redirect()->back();
        } else {
            Session::flash('message', __('The combination customer number/invoice number is not valid. Try again.'));
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }
    }
}
