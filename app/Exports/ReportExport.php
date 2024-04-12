<?php 

namespace App\Exports;

use App\Models\Transactions;
use App\Services\Implementations\Dynamics\Auth\ClientCredentialsAuth;
use Illuminate\Support\Facades\Config;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use SaintSystems\OData\ODataClient;


class ReportExport implements FromView, WithStyles, ShouldAutoSize
{
    use Exportable;

    public $oDataClient = null;

    public function __construct()
    {
        $this->oDataClient = new ODataClient(Config::get('dynamics.resource_data'), function ($request) {
            $request->headers['Content-Type'] = 'application/json';
            $authService = new ClientCredentialsAuth();
            $accessToken = $authService->authorize();
            $request->headers['Authorization'] = "Bearer {$accessToken}";
        });
    }

    public function view(): View
    {
        $yesterday = date("Y-m-d", strtotime( '-1 days' ) );
        $transactions = Transactions::whereDate('created_at', $yesterday )->where('status', 'approved')->get();

        foreach($transactions as $transaction) {
            foreach($transaction->invoices()->get()->toArray() as $invoice) {
                $transaction['invoices'] = $transaction->invoices()->get()->toArray();
                $transaction['user'] = $transaction->user()->get()->toArray();
                $transaction['accountNum'] = $this->getInvoice($invoice['invoice_id']);
            }
        }
        
        return view('templates.reports', ['transactions' => $transactions->toArray()]);
    }

    public function getInvoice($invoiceNum) {
        // first look into not paid transaction
        $unpaid = $this->oDataClient->from('CustTransOpens')
            ->where('Invoice', '=', $invoiceNum)
            ->get();
        
        $unpaidInvoices = array_column($unpaid->toArray(), 'AccountNum');

        if(!empty($unpaidInvoices)) {
            return $unpaidInvoices[0];
        }

        // as fallback check the unpaid
        $paid = $this->oDataClient->from('SalesInvoiceJournalHeaders')
            ->where('InvoiceNumber', '=', $invoiceNum)
            ->get();
    
        $paidInvoices = array_column($paid->toArray(), 'InvoiceCustomerAccountNumber');
        
        if(!empty($paidInvoices)) {
            return $paidInvoices[0];
        }

        return '' ;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'E' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                ],
            ],
            'F' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                ],
            ],
            'B4' => [
                'font' => ['bold' => true, 'size' => 16],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
            ],
            'B6' => [
                        'font' => ['bold' => true],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_CENTER,
                            'wrapText' => true,
                        ],
                        'borders' => [
                                        'bottom' => ['borderStyle' => Border::BORDER_MEDIUM],
                                        'top' => ['borderStyle' => Border::BORDER_MEDIUM],
                                        'left' => ['borderStyle' => Border::BORDER_MEDIUM],
                                        'right' => ['borderStyle' => Border::BORDER_MEDIUM],
                                    ]
                    ],
            'C6' => [
                        'font' => ['bold' => true],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_CENTER,
                            'wrapText' => true,
                        ],
                        'borders' => [
                                        'bottom' => ['borderStyle' => Border::BORDER_MEDIUM],
                                        'top' => ['borderStyle' => Border::BORDER_MEDIUM],
                                        'left' => ['borderStyle' => Border::BORDER_MEDIUM],
                                        'right' => ['borderStyle' => Border::BORDER_MEDIUM],
                                    ]
                    ],
            'D6' => [
                        'font' => ['bold' => true],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_CENTER,
                            'wrapText' => true,
                        ],
                        'borders' => [
                                        'bottom' => ['borderStyle' => Border::BORDER_MEDIUM],
                                        'top' => ['borderStyle' => Border::BORDER_MEDIUM],
                                        'left' => ['borderStyle' => Border::BORDER_MEDIUM],
                                        'right' => ['borderStyle' => Border::BORDER_MEDIUM],
                                    ]
                    ],
            'E6' => [
                        'font' => ['bold' => true],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_CENTER,
                            'wrapText' => true,
                        ],
                        'borders' => [
                                        'bottom' => ['borderStyle' => Border::BORDER_MEDIUM],
                                        'top' => ['borderStyle' => Border::BORDER_MEDIUM],
                                        'left' => ['borderStyle' => Border::BORDER_MEDIUM],
                                        'right' => ['borderStyle' => Border::BORDER_MEDIUM],
                                    ]
                    ],
            'F6' => [
                        'font' => ['bold' => true],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_CENTER,
                            'wrapText' => true,
                        ],
                        'borders' => [
                                        'bottom' => ['borderStyle' => Border::BORDER_MEDIUM],
                                        'top' => ['borderStyle' => Border::BORDER_MEDIUM],
                                        'left' => ['borderStyle' => Border::BORDER_MEDIUM],
                                        'right' => ['borderStyle' => Border::BORDER_MEDIUM],
                                    ]
                    ],
        ];
    }

}