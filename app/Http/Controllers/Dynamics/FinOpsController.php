<?php

namespace App\Http\Controllers\Dynamics;
use App\Http\Controllers\Controller;
use App\Services\Contracts\Dynamics\FinOps\IFinOpsService;
use SaintSystems\OData\IODataClient;
use Illuminate\Support\Facades\Auth;

class FinOpsController extends Controller
{
    private IFinOpsService $finOpsService;
    private IODataClient $oDataClient;

    public function __construct(IFinOpsService $finOpsService, IODataClient $oDataClient)
    {
        $this->finOpsService = $finOpsService;
        $this->oDataClient = $oDataClient;
    }

    public function call()
    {
        $data = $this->oDataClient->from('SalesInvoiceJournalHeaders')
            ->take(1)
            ->get();
            return response()->json($data);
    }
}
