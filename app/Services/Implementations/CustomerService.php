<?php

namespace App\Services\Implementations;

use App\Mail\EInvoiceMail;
use App\Models\UserAccounts;
use App\Services\Contracts\Dynamics\FinOps\IFinOpsService;
use App\Services\Implementations\Dynamics\FinOps\FinOpsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

class CustomerService
{
    public function __construct()
    {
    }

    public function getCustomerDetails()
    {
        return Auth::user()->toArray();
    }

    public function getRelatedAccounts()
    {
        return UserAccounts::where('user_id', Auth::user()->id)->get()->toArray();
    }

    public function addCustomerAccounts($data)
    {
        return UserAccounts::firstOrCreate([
            'user_id' => Auth::user()->id,
            'account_id' => $data['account_id'],
            'invoice_id' => $data['invoice_id'],
            'customer_name' => $data['customerName']
        ]);
    }

    public function getCustomerAccounts($id)
    {
        return UserAccounts::where()->get();
    }

    public function removeCustomerAccount($account_id)
    {
        return UserAccounts::where('account_id', '=', $account_id)->delete();
    }

    public function emailSubscribeForEInvoice($data)
    {   
        return Mail::to("npetrovski@ohanaone.mk")->send(new EInvoiceMail($data));
    }
}