@extends('layouts.app')

@section('content')
    <div
            style='background-image: url("{{ asset('/images/homepage.png') }}"); background-repeat: no-repeat; background-size: cover;'>
        <div class="container m-5 einvoicecontainer">
            <div class="p-3">
                <div class="row">
                    <form method="POST" action="{{ route('sendEInvoice') }}" class="form" id="eInvoice" name="eInvoice">
                        @if(Session::has('message'))
                            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-md-1 col-xs-2 form-inline text-right">
                                <div style="width:32px"></div>
                            </div>
                            <div class="col-md-10 col-xs-9">
                                <a href="#" class="go-back-btn" onclick="history.back()"><i class="fa-sharp fa-solid fa-chevron-left"></i> {{__("Go Back")}}</a>
                                <br><br>
                                <h2>
                                    {{ __('Receive your bills on your email address.') }}
                                </h2>
                                <br>
                                {{ __('Fill out the form below and from now on receive you invoices directly on your email.') }}
                                <br><br
                                >
                                <div class="">
                                    <ul> {{__("With the e-invoice service you can always be sure that;")}}
                                        <li>{{__("You will receive the bills in a timely manner.")}}</li>
                                        <li>{{__("Bills will not be lost on the way to yours mail box.")}}</li>
                                        <li>{{__("If you have multiple user numbers, you will receive the bills in one place.")}}</li>
                                    </ul>
                                </div>
                                <br> {{__("Registration is simple and fast, and by filling out this form you immediately become a user of the e-invoice service.")}} <br
                                ><br></div>
                        </div>
                        <div class="row">
                            <div class="col-md-10 offset-1">
                                <div class="mb-3 d-flex align-content-center">
                                    <img class="m-2" src="{{ asset('/images/svg/icon_personal.svg') }}" alt="icon_personal">
                                    <div>
                                        <input
                                                class="form-control @error('name') is-invalid @enderror"
                                                name="name"
                                                type="text"
                                                placeholder="{{__('Customer name')}}"
                                                value="{{ old('name') }}"
                                                required
                                        >
                                    </div>
                                </div>
                                <div class="mb-3 d-flex align-content-center">
                                    <img class="m-2" src="{{ asset('/images/svg/icon_address.svg') }}" alt="icon_address">
                                    <div>
                                        <input
                                                class="form-control @error('address') is-invalid @enderror"
                                                name="address"
                                                type="text"
                                                placeholder="{{__('Address')}}"
                                                value="{{ old('address') }}"
                                                required
                                        >
                                    </div>
                                </div>
                                <div class="mb-3 d-flex align-content-center">
                                    <img class="m-2" src="{{ asset('/images/svg/icon_phone.svg') }}" alt="icon_phone">
                                    <div>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <select id="countryList" class="form-control" name="phonePrefix" required>    
                                                    @foreach (getPhonePrefixes() as $prefix => $name)
                                                        <option phonecode="{{ $prefix }}" 
                                                                value="{{ $prefix }}" 
                                                                id="shop-country" {{$prefix == "389" ? "selected" : ""}}>{{ $name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <input
                                                    class="form-control @error('phone') is-invalid @enderror"
                                                    name="phone"
                                                    placeholder="3897xxxxxxx"
                                                    type="number"
                                                    value="{{ old('phone') }}"
                                                    required
                                            >
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3 d-flex align-content-center">
                                    <img class="m-2" src="{{ asset('/images/svg/icon_email.svg') }}" alt="icon_email">
                                    <div>
                                        <input
                                                class="form-control @error('email') is-invalid @enderror"
                                                name="email"
                                                type="email"
                                                placeholder="{{__('Email Address')}}"
                                                value="{{ old('email') }}"
                                                title="Required field"
                                                required
                                        >
                                    </div>
                                </div>
                                <div class="mb-3 d-flex align-content-center">
                                    <img class="m-2" src="{{ asset('/images/svg/icon_client.svg') }}" alt="icon_client">
                                    <div>
                                        <input
                                                class="form-control @error('accountNumber') is-invalid @enderror"
                                                name="accountNumber"
                                                type="text"
                                                placeholder="{{__('Customer number')}}"
                                                value="{{ old('accountNumber') }}"
                                                required
                                        >
                                    </div>
                                </div>
                                <div class="mb-3 d-flex align-content-center">
                                    <img class="m-2" src="{{ asset('/images/svg/icon_invoice.svg') }}" alt="icon_invoice">
                                    <div>
                                        <input
                                                class="form-control @error('invoiceNumber') is-invalid @enderror"
                                                name="invoiceNumber"
                                                type="text"
                                                placeholder="{{ __('Invoice number') }}"
                                                value="{{ old('invoiceNumber') }}"
                                                required
                                        >
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-xs-2 form-inline text-right">
                                <div style="width:32px"></div>
                            </div>
                            <div class="col-md-10"><br>
                                <div>
                                    {{ __('The entered data is correct and refers to a customer number and measurement point in my possession/right of usage. I am consent that my personal data is processed for the purpose of services payment. For every change in my personal data I will notify EVN Macedonia and the companies where it is the only or a majority shareholder within 30 days of the change. Otherwise, every delivery that the company makes at the address stated in this form is considered legitimate.') }}
                                </div>
                                <br>
                                <hr style="background:#1b3a8e; height:3px">
                                <br>
                                <div class="form-group has-feedback">
                                    <input
                                            id="terms"
                                            name="directMarketing"
                                            type="checkbox"
                                            class="ng-untouched ng-pristine ng-valid"
                                            required
                                    >
                                    <span class="">
                                                {{ __('I agree to receive invoices only electronically to the specified e-mail address.') }}
                                        </span>
                                </div>
                                <br>
                                <div class="form-group">
                                    {{ __('For each change of method
                                     on delivery of the invoice feel free to contact the contact center of
                                     NIKOB and the companies where he is the sole/majority partner, of
                                     phone numbers 089089089 or 02 3205 000 or by e-mail
                                     info@nikob.mk.')
                                    }}
                                    <br><br>
                                    {{ __("For all questions regarding the right to process your personal data, please contact us ") }}
                                    <a href='mailto:info@nikob.mk'>{{__("here")}}</a>
                                    <br><br>
                                    {{ __('By clicking the CONTINUE button you confirm that you have been contacted and you agree with the terms of the privacy policy of EVN Macedonia and the companies where it is an only or majority shareholder.  ')
                                        }}
                                </div>
                                <br>
                                <div class="float-end">
                                    <button type="submit" class="btn btn-lg btn-primary">
                                        {{ __('Continue') }} <i class="fa-sharp fa-solid fa-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
