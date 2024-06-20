@extends('welcome', [
    'contentPart' => 'invoices'
    ])

@section('content-part')
    <h1 class="nikob-title">{{__("Unpaid invoices")}}</h1>
    <div class="accordion" id="invoices">
        @foreach($accounts as $account)
        <div class="accordion-item">
            <div class="card mb-3">
                <div class="card-header accordion-header d-flex justify-content-between" type="button" data-bs-toggle="collapse" data-bs-target="#{{$account['account_id']}}" aria-expanded="true" aria-controls="{{$account['account_id']}}">
                    <div class="float-left">{{ $account['account_id'] }}: {{ $account['customer_name'] }}</div>
                    <div class="float-right">{{ number_format($account['unpaid'], 2)}} {{__('den')}}</div>
                </div>
                <div class="card-body p-4 accordion-collapse collapse show" data-bs-parent="#invoices" id="{{$account['account_id']}}">
                    <form method="POST" action="/pay" class="form full-width">
                        @csrf
                        <table id="invoices_table" class="display" style="width:100%">
                            <thead>
                            <tr>
                                <th></th>
                                <th class="text-center">{{__("Invoice number")}}</th>
                                <th class="text-center">{{__("Customer number")}}</th>
                                <th class="text-center">{{__("Date")}}</th>
                                <th class="text-center">{{__("Due date")}}</th>
                                <th class="text-center">{{__("Currency")}}</th>
                                <th class="text-center">{{__("Amount")}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($account['invoices'] as $invoice)
                                @if($invoice['AmountCur'] > 0)
                                    <tr>
                                        <td class="select-invoice">
                                            <input type="checkbox" class="pay_invoice" name="invoice_id[]" <?php echo (isset($invoice['lastTransaction']) && in_array($invoice['lastTransaction']['status'], [ 'approved'])) ? 'disabled':'';?> data="{{ $invoice['AmountCur']  }}"/>
                                            @if(isset($invoice['lastTransaction']))
                                                @switch($invoice['lastTransaction']['status'])
                                                    @case('approved')
                                                        <i class="fa-regular fa-circle-question" data-toggle="tooltip"  data-placement="bottom" title="{{__('Your transaction is being processed')}}"></i>
                                                        @break
                                                    @case('declined')
                                                        <i class="fa-regular fa-circle-xmark" data-toggle="tooltip"  data-placement="bottom" title="{{__('Your last transaction has been declined')}}"></i>
                                                        @break
                                                    @case('error')
                                                        <i class="fa-solid fa-triangle-exclamation" data-toggle="tooltip"  data-placement="bottom" title="{{__('There was an error during the last payment attempt')}}"></i>
                                                        @break
                                                @endswitch
                                            @endif
                                        </td>
                                        <td>{{ $invoice['AccountNum']  }}</td>
                                        <td>{{ \Carbon\Carbon::parse($invoice['TransDate'])->format('d-m-Y')}}</td>
                                        <td>{{ \Carbon\Carbon::parse($invoice['DueDate'])->format('d-m-Y')}}</td>
                                        <td>{{ number_format($invoice['AmountCur'], 2)  }}</td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                        <div id="payment_summary" class="align-left">
                            <span>{{__("Billing total")}}: <b>0.00</b> {{__("den")}}</span>
                            <button type="submit" class="btn-primary btn-auto" disabled >{{__("Continue")}} <i class="fa-sharp fa-solid fa-chevron-right"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endsection
