<?php

namespace App\Console\Commands;

use App\Exports\ReportExport;
use App\Models\Transactions;
use Illuminate\Support\Facades\Mail;
use SaintSystems\OData\IODataClient;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Excel as BaseExcel;
use Maatwebsite\Excel\Facades\Excel;

class CreateReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nikob:create-report';

    private ?IODataClient $oDataClient = null;

    public function __construct(IODataClient $oDataClient)
    {
        parent::__construct();
        $this->oDataClient = $oDataClient;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Mail::send(['html'=>'email_templates.reports'], [], function($message) {
            $to = explode(',', env('REPORT_EMAILS'));
            $excelFile = Excel::raw(new ReportExport(), BaseExcel::XLSX);
            $message->to($to);
            $message->from('no-replay@nikob.com.mk', env("APP_NAME"));
            $message->subject('ИЗВЕШТАЈ ЗА ПЛАТЕНИ СМЕТКИ');
            $message->attachData($excelFile, 'ИЗВЕШТАЈ - '.date('d M Y').'.xlsx');
       
       });
    }
}
