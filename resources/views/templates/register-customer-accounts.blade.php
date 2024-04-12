@extends('welcome', [
    'contentPart' => 'new_account'
    ])

@section('content-part')
    <h1 class="nikob-title">
        {{__('Add new customer')}}
    </h1>
    @if(session('error'))
            <p class="alert alert-danger">{{ session('error') }}</p>
    @endif
    <div class="card">
        <div class="card-header text-uppercase">{{ Auth::user()->name.' '.Auth::user()->surname }}</div>
        <div class="card-body p-4" style="width: 100%">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <h1 class="nikob-subtitle">{{__("Customer informations")}}</h1>
                    <hr>
                    <form class="form" id="pay-fast-form" method="POST" action="{{ route('connectAccounts') }}">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">КУП</span>
                                    </div>
                                    <input id="account_id" type="number" min="0" class="form-control with-border @error('account_id') is-invalid @enderror" placeholder="{{ __('Customer number') }}" name="account_id" value="{{ old('account_id') }}" required autocomplete="account_id" autofocus>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <input id="invoice_id" type="text" class="form-control with-border text-left @error('invoice_id') is-invalid @enderror" placeholder="{{ __('Invoice number') }}" name="invoice_id" value="{{ old('invoice_id') }}" required autocomplete="invoice_id" autofocus>
                            </div>
                        </div>
                        <div class="col-md-12 align-content-end">
                            <button type="submit" class="btn btn-primary text-capitalize d-inline-block w-auto">
                                {{__("Connect customer")}} <i class="fa-sharp fa-solid fa-plus"></i>
                            </button>
                        </div>

                    </form>
                </div>
                <div class="col-md-6">
                    <h1 class="nikob-subtitle">{{__("Your customers")}}</h1></h1>
                    <hr>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                @if(!empty($accounts))
                                <div class="d-flex justify-content-between align-items-center activity">
                                    <div><span class="opacity-25">{{__("Customer name")}}</span></div>
                                    <div><span class="opacity-25">{{__("Customer number")}}</span></div>
                                    <div class="icons"></div>
                                </div>
                                <div class="mt-3">
                                    <ul class="list list-inline">
                                        @foreach($accounts as $account)
                                        <li class="d-flex justify-content-between mb-3">
                                            <div class="d-flex flex-row align-items-center">
                                                <div class="ml-2">
                                                    <h6 class="mb-0">{{ $account['customer_name']  }}</h6>
                                                    <div class="d-flex flex-row mt-1 text-black-50 date-time">
                                                        <div><i class="fa fa-calendar-o"></i><span class="ml-2">{{date('d-m-Y', strtotime($account['created_at'])) }}</span></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-row align-items-center">
                                                <div class="d-flex flex-column mr-2">
                                                    {{ $account['account_id']  }}
                                                </div>
                                            </div>
                                            <div class="d-flex flex-row align-items-center">
                                                <a class="btn btn-danger" href="{{ route('removeAccount', ['account_id' => $account['account_id']]) }}" 
                                                data-toggle="tooltip"  data-placement="bottom" title="{{__('Remove this customer')}}">
                                                    <i class="fa fa-close"></i>
                                                </a>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @else 
                                    <p class="text-muted text-center">{{__("There is no customer added")}}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
