<?php

namespace App\Http\Controllers;

use App\Models\Invoices;
use App\Models\Transactions;
use App\Services\Contracts\Dynamics\FinOps\IFinOpsService;
use Auth;
use Illuminate\Http\Request;
use App\Services\Payment\NestPay;
use SaintSystems\OData\IODataClient;
use Carbon;
use PHPUnit\Framework\Attributes\Test;

class PayController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(IFinOpsService $finOpsService, IODataClient $oDataClient)
    {
        $this->finOpsService = $finOpsService;
        $this->oDataClient = $oDataClient;
    }

    /**
     * First page for paying invoice
     */
    public function index(Request $request)
    {
        $total = 0;
        foreach($request->input('invoice_id') as $invoice ) {
            print_r($invoice);
            $res = $this->finOpsService->getInvoice($invoice);
            if ($res != null && $res[0]['AmountCur'] != null)
                $total += $res[0]['AmountCur'];
        }

        date_default_timezone_set('Europe/Skopje');
        $currentDateTime = date('Y-m-d H:i:s');

        $TID = Transactions::create([
            'transaction_id' => null,
            'amount' => $total,
            'created_at' => $currentDateTime,
            'notify' => Auth::user()->email,
            'user_id' => Auth::user()->id,
            'logs' => '[]',
            'type' => 'pay'
        ])->id;

        foreach($request->input('invoice_id') as $invoice ) {
            Invoices::create([
                'invoice_id' => $invoice,
                'tid' => $TID
            ]);
        }

        return view('pay.index')->with('transactionID', $TID)??'';
    }
    public function form($TID)
    {
        $transaction = Transactions::findOrFail($TID);

        $nestPay = new NestPay();

        $nestPay->amount = $transaction->amount;
        $nestPay->orderID = $transaction->id;
        $form = $nestPay->form();
        return view('pay.form')->with('form', $form);
    }

    public function callback(Request $request)
    {
        $data = $request->post();

        session()->forget('fastInvoices');

        $nestPay = new NestPay();
        // $status = $nestPay->validate($data);

        $transaction = Transactions::findOrFail($data['oid']);

        // update transaction after callback
        if (!empty($data['Response']))
            $transaction->status = strtolower($data['Response']);

        if (!empty($data['ErrMsg']))
            $transaction->error_message = $data['ErrMsg'];

        if (!empty($data['TransId']))
            $transaction->transaction_id = $data['TransId'];

        if (!empty($data['amount']))
            $transaction->amount = $data['amount'];

        $transaction->save();

        $view = $data['Response'] == 'Approved' ? 'success' : 'fail';

        if($transaction->type === 'pay-fast') {
            $redirect = '/pay-fast/status';
        } else {
            $redirect = '/pay/callback/'.$view;
        }

        return view("pay.callback")->with('redirect', $redirect);
    }

    public function callbackStatus( Request $request, $status )
    {
        return view("pay.$status");
    }
}
