@extends('layouts.app')

@section('content')
<div id="home">
    <div class="container">
        <div id="home_wrapper">
            <ul id="menu">
                <li>
                    <a href="{{route('eInvoice')}}">
                        <img src="{{asset('/images/svg/e_invoice.svg')}}"/>
                        <span data-toggle="tooltip" data-boundary="window" data-placement="left" title="{{__('Sign in to receive invoices to your email address')}}"> {{ __("E-invoice") }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('payFastIndex')}}">
                        <img src="{{asset('/images/svg/pay_fast.svg')}}"/>
                        <span data-toggle="tooltip"  data-placement="left" title="{{__('Quick payment of invoices without the need to log in')}}">{{ __("Pay Fast") }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('login')}}">
                        <img src="{{asset('/images/svg/star.svg')}}"/>
                        <span  data-toggle="tooltip"  data-placement="bottom" title="{{__('Fast and secure payment of your invoices')}}">{{ __("Nikob User portal") }}</span>
                    </a>
                </li>
            </ul>
            <a href="{{route('information')}}" id="more" class="text-uppercase">{{ __('More information') }} <i class="fa-sharp fa-solid fa-chevron-right"></i></a>
        </div>
    </div>
</div>
@endsection
