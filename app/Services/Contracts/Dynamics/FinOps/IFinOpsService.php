<?php

namespace App\Services\Contracts\Dynamics\FinOps;

interface IFinOpsService
{
    public function getCustomerDetails($customerAccount);
    public function updateCustomerDetails();
    public function getCustomerInvoices($customerAccount);
    public function validateCustomerInvoice($data);
    public function getInvoice($invoiceNum);
    public function getAllInvoices($customerAccount);
    public function downloadInvoice($invoiceNumber);
}
