@extends('welcome', [
    'contentPart' => 'all-invoices'
    ])

@section('content-part')
    <h1 class="nikob-title">{{__("Download invoices")}}</h1>
    <div class="accordion" id="invoices">
        @foreach($accounts as $account)
            <div class="accordion-item">
                <div class="card mb-3">
                    <div class="card-header text-uppercase accordion-button accordion-header" type="button" data-bs-toggle="collapse" data-bs-target="#{{$account['account_id']}}" aria-expanded="true" aria-controls="{{$account['account_id']}}">{{ $account['account_id'] }}: {{ $account['customer_name'] }}</div>
                    <div class="card-body p-4 accordion-collapse collapse show" data-bs-parent="#invoices" id="{{$account['account_id']}}">
                        <form method="POST" action="/pay" class="form full-width">
                            @csrf
                            <table id="all_invoices_table" class="table table-borderless hover" style="width:100%">
                                <thead>
                                <tr>
                                    <th class="text-center">{{__("Invoice number")}}</th>
                                    <th class="text-center">{{__("Customer number")}}</th>
                                    <th class="text-center">{{__("Date")}}</th>
                                    <th class="text-center">{{__("Currency")}}</th>
                                    <th class="text-center">{{__("Amount")}}</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($account['invoices'] as $invoice)
                                    @if($invoice['TotalInvoiceAmount'] > 0)
                                    <tr>
                                        <td>{{ $invoice['InvoiceNumber']  }}</td>
                                        <td>{{ $invoice['InvoiceCustomerAccountNumber']  }}</td>
                                        <td>{{ \Carbon\Carbon::parse($invoice['InvoiceDate'])->format('d-m-Y')}}</td>
                                        <td>{{ $invoice['CurrencyCode']  }}</td>
                                        <td>{{ number_format($invoice['TotalInvoiceAmount'], 2)  }}</td>
                                        <td>
                                            <a class="btn btn-outline-info" href="{{ route('downloadInvoice', $invoice['InvoiceNumber']) }}"
                                            data-toggle="tooltip" title="{{__('Download')}}">
                                                <i class="fa fa-download"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
