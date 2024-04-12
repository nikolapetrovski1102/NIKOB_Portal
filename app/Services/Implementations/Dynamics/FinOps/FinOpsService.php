<?php

namespace App\Services\Implementations\Dynamics\FinOps;

use App\Models\Invoices;
use App\Services\Contracts\Dynamics\FinOps\IFinOpsService;
use Illuminate\Support\Enumerable;
use SaintSystems\OData\IODataClient;
use Illuminate\Support\Collection;


class FinOpsService implements IFinOpsService
{
    private ?IODataClient $oDataClient = null;

    public function __construct(IODataClient $oDataClient)
    {
        $this->oDataClient = $oDataClient;
    }

    public function getCustomerDetails($customerAccount)
    {
        $data = $this->oDataClient->from('CustomersV3')
            ->select()
            ->where('CustomerAccount', '=', $customerAccount)
            ->get();
        return $data->toArray()[0];
    }
    public function updateCustomerDetails()
    {
        return '';
    }
    public function getCustomerInvoices($customerAccounts)
    {
        $return = [];
        foreach ($customerAccounts as &$customerAccount) {
            $data = $this->oDataClient->from('CustTransOpens')
                ->where('AccountNum', '=', $customerAccount['account_id'])
                ->order('TransDate', 'asc')
                ->get();

            // dd($data);
            $customerAccount['invoices'] = $data->toArray();
            $return[] = $customerAccount;
        }

        return $return;
    }

    public function validateCustomerInvoice($data)
    {
        $unpaid = $this->oDataClient->from('CustTransOpens')
            ->where('AccountNum', '=', $data['account_id'])
            ->get();

        $paid = $this->oDataClient->from('SalesInvoiceJournalHeaders')
            ->where('InvoiceCustomerAccountNumber', '=', $data['account_id'])
            ->order('InvoiceDate', 'asc')
            ->get();

        $paidInvoices = array_column($paid->toArray(), 'InvoiceNumber');
        $unpaidInvoices = array_column($unpaid->toArray(), 'Invoice');

        $invoiceList = array_merge($paidInvoices, $unpaidInvoices);

        $invoiceAccountValid = in_array($data['invoice_id'], $invoiceList);

        if ($invoiceAccountValid) {
            $name = $this->oDataClient->from('CustomersV3')
                ->where('CustomerAccount', '=', $data['account_id'])
                ->select('OrganizationName')
                ->get()->toArray();
        } else {
            return [ 'isValid' => $invoiceAccountValid ];
        }


        return [
            'isValid' => $invoiceAccountValid,
            'customerName' => $name[0]['OrganizationName']
        ];
    }


    public function getInvoice($invoiceNum) {
        try {
            $data = $this->oDataClient->from('CustTransOpens')
                ->where('Invoice', '=', $invoiceNum)
                ->order('TransDate', 'asc')
                ->get();
    
            return $data->toArray();
        } catch (\Exception $e) {
            error_log('Error fetching invoice: ' . $e->getMessage());
            
            return null;
        }
    }
    

    public function getAllInvoices($customerAccounts)
    {
        $return = [];
        foreach ($customerAccounts as &$customerAccount) {
            $data = $this->oDataClient->from('SalesInvoiceJournalHeaders')
                ->where('InvoiceCustomerAccountNumber', '=', $customerAccount['account_id'])
                ->order('InvoiceDate', 'asc')
                ->get();

            $customerAccount['invoices'] = $data->toArray();
            $return[] = $customerAccount;
        }

        foreach ($return as &$customerAccount) {
            foreach ($customerAccount['invoices'] as &$invoice) {
                $status = Invoices::where('invoice_id', $invoice['InvoiceCustomerAccountNumber'])->orderBy('created_at', 'DESC')->first();
                if ($status) {
                    $invoice['lastTransaction'] = $status->transaction()->first()->toArray();
                }
            }
        }

        return $return;
    }

    public function downloadInvoice($invoiceNumber)
    {
        return true;
    }

}

