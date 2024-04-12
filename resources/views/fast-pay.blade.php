@extends('welcome', [
        'contentPart' => 'homepage',
        'sidebar' => 0
    ])

@section('content-part')
<div class="content pay-fast-content">
    <content class="m-0">
        <div class="container p-0">
            @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
            @endif
            <ul id="steps" class="d-flex justify-content-between p-0">
                <li class="{{$data['action'] == '' || $data['action'] == 'status' || !empty($data['invoices']) ? 'current' : ''}}">
                    <a href="{{ route('payFastIndex')}}">{{__("Invoice data")}} <i class="fa fa-fw fa-chevron-right"></i></a>
                </li>
                <li class="{{($data['action'] == 'payment' || $data['action'] == 'status' || !empty($data['invoices'])) ? 'current' : ''}}" >
                    <a href="{{ route('payFastIndex')}}/payment">{{__("Make payment")}} <i class="fa fa-fw fa-chevron-right"></i></a>
                </li>
                <li class="{{$data['action'] == 'status' ? 'current' : ''}}">
                    <a href="#payment_status">{{__("Payment status")}}</a>
                </li>
            </ul>

            <div class="row">
                <div class="col-sm-12" >
                    <div class="tab-content fast-pay">
                        <div class="tab-pane {{ $data['action'] == '' ? 'active' : ''}}" id="invoice_data">
                            <div class="container">
                                <div class="row mb-5">
                                    <div class="col-xs-12 col-sm-5 col-md-4 p-0">
                                        <span class="text-uppercase">{{__("Step 1")}}</span>
                                        <hr>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-7 p-0">
                                        <span>{{__("Invoice data")}}</span>
                                        <hr>
                                        {{__("Pay your invoice without signing in to nikob.com.mk, simply enter your customer number and number of invoice that you want to pay.")}}
                                    </div>
                                </div>
                                <div class="row ">
                                    <div class="col-xs-12 col-sm-5 col-md-4 p-0">
                                        <span class="text-uppercase">{{__("Enter invoice")}}</span>
                                        <hr>
                                        <form class="form" id="pay-fast-form" method="POST" action="{{route('addFastInvoice')}}">
                                            @csrf
                                            <div class="row mb-3">
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1">КУП</span>
                                                        </div>
                                                        <input id="account_id" type="number" min="0" class="form-control with-border @error('account_id') is-invalid @enderror" placeholder="{{ __('Customer number') }}" name="account_id" value="{{ old('account_id') }}" required autocomplete="account_id" autofocus>
                                                    </div>
                                                    @error('account_id')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-12">
                                                    <input id="invoice_id" type="text" class="form-control with-border text-left @error('invoice_id') is-invalid @enderror" placeholder="{{ __('Invoice number') }}" name="invoice_id" value="{{ old('invoice_id') }}" required autocomplete="invoice_id" autofocus>

                                                    @error('invoice_id')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12 align-content-end">
                                                <button type="submit" class="btn btn-primary text-capitalize d-inline-block w-auto">
                                                    {{ __('Confirm') }}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-7 p-0">
                                        <span class="text-uppercase">{{__("Invoices list") }}</span>
                                        <hr>
                                        @php 
                                            $total = 0; 
                                            $invoices = [];
                                        @endphp
                                        @if(isset($data['invoices']['invoices']))
                                            @foreach($data['invoices']['invoices'] as $key => $invoice)
                                                @php 
                                                    $total += $invoice['AmountCur']; 
                                                    $invoices[] = $invoice['Invoice'];
                                                @endphp
                                                <div class="invoice-details">
                                                    <a href="javascript:;" onclick="deleteInvoice('{{route('payFastIndex')}}/delete/{{$key}}')" data-toggle="tooltip" data-placement="left" title="Избриши ја фактурата"><i class="fa-solid fa-trash"></i></a>
                                                    <div class="d-flex justify-content-between p-0">{{__("Customer number") }}: <span>{{$invoice['AccountNum']}}</span></div>
                                                    <div class="d-flex justify-content-between p-0">{{__("Invoice number") }}: <span>{{$invoice['Invoice']}}</span></div>
                                                    <div class="d-flex justify-content-between p-0">{{__("Date") }}: <span>{{ \Carbon\Carbon::parse($invoice['TransDate'])->format('d-m-Y')}}</span></div>
                                                    <div class="d-flex justify-content-between p-0">{{__("Amount")}}: <b>{{ number_format($invoice['AmountCur'], 2)}} ден</b></div>
                                                </div>
                                            @endforeach
                                        @endif
                                        <div class="d-flex justify-content-between align-items-center m-t-5">
                                            <span>{{__("Total")}}:</span>
                                            <div class="text-right d-block" id="total">
                                                {{ number_format($total, 2)}} ден
                                            </div>
                                        </div>
                                        <hr>
                                        @if(!empty($data['invoices']['invoices']))
                                        <form class="form" method="POST" action="{{route('initFastPayment')}}">
                                            @csrf
                                            <div class="row form d-flex align-items-center">
                                                <div class="col-xs-6 col-sm-6 col-md-6 pr-4 text-right">{{__("Email Address for confirmation")}}</div>
                                                <div class="col-xs-6 col-sm-6 col-md-6 p-0">
                                                    <input id="email_address" type="email" class="form-control with-border @error('email_address') is-invalid @enderror" placeholder="{{ __('Email Address') }}" name="email_address" value="{{ $data['invoices']['email']??'' }}" required>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mt-4 form">
                                                <span></span>
                                                <button class="btn-primary text-capitalize d-inline-block w-auto">
                                                    {{ __('Continue') }}   <i class="fa-sharp fa-solid fa-chevron-right"></i>
                                                </button>
                                            </div>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(!empty($data['invoices']['transaction']))
                        <div class="tab-pane {{ $data['action'] && $data['action'] == 'payment' ? 'active' : ''}}" id="payment_data">
                            <div class="container">
                                <iframe src="/pay/form/{{ $data['invoices']['transaction'] }}" style="height:100%; min-height:450px; width: 100%;"></iframe>
                            </div>
                        </div>
                        @endif

                        <div class="tab-pane {{ $data['action'] && $data['action'] == 'status' ? 'active' : ''}}" id="payment_status">
                            <div class="container">
                                <div class="row justify-content-center">
                                    <div class="col-md-4 form" id="transaction-status">
                                        @if(!empty($data['transaction']) && $data['transaction']['status'] == 'Approved')
                                        <i class="fa-solid fa-check"></i>
                                        <h1>{{__("Transaction successfull")}}</h1>
                                        <p class="success">{{__("Thank you")}}</p>
                                        @else 
                                        <i class="fa-solid fa-exclamation"></i>
                                        <h1>{{__("Transaction declined")}}</h1>
                                        <p class="declined">{{__("Try again")}}</p>
                                        @endif
                                        <a href="{{route('payFastIndex')}}" class="btn-text">{{__("Go back")}}</a>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
            </div>
        </div>
    </content>
</div>
@endsection

<script>
    function deleteInvoice(url) {
        if (window.confirm('{{__("Are you sure you want to delete the invoice from the payment list?")}}'))
        {
            window.location = url;
        }
    }
</script>