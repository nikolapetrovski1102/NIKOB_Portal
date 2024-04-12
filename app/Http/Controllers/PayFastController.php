<?php

namespace App\Http\Controllers;

use App\Models\Invoices;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Services\Contracts\Dynamics\FinOps\IFinOpsService;

class PayFastController extends Controller
{
    private $prefix = "КУП";
    private $finOpsService;
    public function __construct(IFinOpsService $finOpsService)
    {
        $this->finOpsService = $finOpsService;
    }
    public function index($action = '')
    {
        $fastInvoices = session('fastInvoices') ?? [];

        if($action == 'payment' && empty($fastInvoices['invoices'])) {
            return redirect('/pay-fast');
        }

        return view('fast-pay')->with('data', ['invoices' => $fastInvoices, 'action' => $action ] );
    }
    function init( Request $request) {
        $fastInvoices = session('fastInvoices') ?? [];
        $invoiceId = $request->input('invoice_id');

        if (array_key_exists($invoiceId, $fastInvoices['invoices']??[])) {
            Session::flash('message', __("Invoice is already added"));
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }

        $res = $this->finOpsService->getInvoice($invoiceId);

        if(empty($res)) {
            Session::flash('message', __("Invoice with that number is not found!"));
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }

        if($res[0]['AccountNum'] != $this->prefix.$request->input('account_id') ) {
            Session::flash('message', __("The invoice number and the customer number must match"));
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }
        
        $fastInvoices['invoices'][$invoiceId] = $res[0];
        session(['fastInvoices' => $fastInvoices]);


        Session::flash('message', __("The invoice has been successfully added for payment"));
        Session::flash('alert-class', 'alert-success');

        return view('fast-pay')->with( 'data', ['invoices' => $fastInvoices, 'action' => '']);
    }
    function delete( Request $request, $id) {
        $fastInvoices = session('fastInvoices') ?? [];
        
        unset($fastInvoices['invoices'][$id]);
        session(['fastInvoices' => $fastInvoices]);

        Session::flash('message', __("An invoice has been successfully deleted from the payment list"));
        Session::flash('alert-class', 'alert-success');

        return redirect()->back();
    }

    function payment( Request $request) {
        $fastInvoices = session('fastInvoices') ?? [];
        $fastInvoices['email'] = $request->input('email_address');

        $total = 0;
        foreach($fastInvoices['invoices'] as $invoice ) {
            $invoices[] = $invoice['Invoice'];
            $total += $invoice['AmountCur'];
        }

        $TID = Transactions::create([
            'transaction_id' => null,
            'amount' => $total,
            'notify' => $fastInvoices['email'],
            'type' => 'pay-fast',
            'logs' => '[]'
        ])->id;

        foreach($fastInvoices['invoices'] as $invoice ) {
            Invoices::create([
                'invoice_id' => $invoice['Invoice'],
                'tid' => $TID
            ]);
        }

        $fastInvoices['transaction'] = $TID;

        session(['fastInvoices' => $fastInvoices]);

        return redirect('/pay-fast/payment');
    }

    function status(Request $request) {
        $fastInvoices = session('fastInvoices') ?? [];

        $transaction = [];
        if( !empty($fastInvoices) )
            $transaction = Transactions::findOrFail($fastInvoices['transaction'])->toArray();

        session()->forget('fastInvoices');

        return view('fast-pay')->with( 'data', [ 'transaction' => $transaction, 'invoices' => $fastInvoices, 'action' => 'status']);
    }
}
