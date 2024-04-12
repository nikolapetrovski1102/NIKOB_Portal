@extends('welcome', [
    'contentPart' => 'transactions'
    ])

@section('content-part')
    <h1 class="nikob-title">{{__('Transactions list')}}</h1>
    <div class="card">
        <div class="card-header text-uppercase">{{ Auth::user()->name.' '.Auth::user()->surname }}</div>
        <div class="card-body p-4">
                @csrf
                <table id="invoices_table" class="display" style="width:100%">
                    <thead>
                    <tr>
                        <th>{{__("Transaction ID")}}</th>
                        <th>{{__("Invoice number")}}</th>
                        <th>{{__("Execution time")}}</th>
                        <th>{{__("Amount")}}</th>
                        <th>{{__("Currency")}}</th>
                        <th>{{__("Status")}}</th>
                        <th>{{__("Error")}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction['transaction_id']  }}</td>
                            <td>
                                @foreach($transaction->invoices as $invoice)
                                    {{ $invoice->invoice_id  }}<br>
                                @endforeach
                            </td>
                            <td>{{ \Carbon\Carbon::parse($transaction['created_at'])->format('d-m-Y H:i')}}</td>
                            <td>{{ number_format($transaction['amount'], 2)  }}</td>
                            <td>MKD</td>
                            <td>
                            @switch($transaction['status'])
                                @case('pending')
                                    {{__('Pending')}}
                                    @break
                                @case('declined')
                                    {{__("Declined")}}
                                    @break
                                @case('approved')
                                    {{__("Approved")}}
                                    @break
                                @case('error')
                                    {{__("Error occured")}}
                                    @break
                                @default
                                    {{__("Processing")}}
                                @endswitch
                            </td>
                            <td>{{ $transaction['error_message']  }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
        </div>
    </div>
@endsection
