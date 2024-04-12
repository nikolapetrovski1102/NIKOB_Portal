<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\UserAccounts;
use App\Services\Contracts\Dynamics\FinOps\IFinOpsService;
use App\Services\Implementations\CustomerService;
use Illuminate\Http\Request;
use Validator;
use SaintSystems\OData\IODataClient;

class ClientsController extends Controller
{
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
    * Return list of clients for selected user
    *
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */

    function index(Request $request) {
        $accounts = UserAccounts::where('user_id', $request->user()->id)->get()->toArray();

        return response()->json([
            'status' => true,
            'accounts' => $accounts,
        ], 200);
    }

    /**
    * Add new client to the user profile
    *
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */

    function store(Request $request) {
        $validate = Validator::make($request->all(), 
            [
                'account_id' => 'required',
                'invoice_id' => 'required'
            ]);

            if($validate->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validate->errors()
                ], 400);
            }
        
        $checkRecord = UserAccounts::where('account_id', $request->input('account_id'))
                            ->where('invoice_id', $request->input('invoice_id'))
                            ->first();

        if ($checkRecord) {
            return response()->json([
                'status' => false,
                'message' => 'This pair account_id/invoice_id is already linked',
            ], 400);
        }

        $data = [
            'account_id' => "КУП" . str_replace('КУП', '', $request->input('account_id')),
            'invoice_id' => $request->input('invoice_id')
        ];
        $response = $this->finOpsService->validateCustomerInvoice($data);
        if ($response['isValid']) {
            $data['customerName'] = $response['customerName'];
            $result = $this->customerService->addCustomerAccounts($data);
            return response()->json([
                'status' => true,
                'message' => 'Client is successfully connected with the account',
            ], 200);

        } else {
            return response()->json([
                'status' => false,
                'message' => 'Invoice and account did not match',
            ], 400);
        }
    }

    /**
    * Return information about selected client
    *
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */

    function show(Request $request) {
        die('show');
    }


    /**
    * Remove client from user profile
    *
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */

    function destroy() {
        die('destroy');
    }
}
