@extends('layouts.app', [ 'menu' => 1])

@php $sidebar = isset($sidebar) ? $sidebar : 1; @endphp
@section('content')
<div class="subheader d-flex justify-content-between ">
    <div>
        @if(Auth::check())
        <div class="dropdown">
            <button class="dropdown-toggle" type="button" id="userDropdown" data-toggle="dropdown"  data-bs-toggle="dropdown"  aria-haspopup="true" aria-expanded="false">
                {{ Auth::check() ? Auth::user()->email : ''}} <i class="fa-solid fa-angle-down"></i>
            </button>

            <div class="dropdown-menu" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="{{route('customer')}}">{{__("Change password")}}</a>
                <a class="dropdown-item" href="{{route('customer')}}">{{__("Change Email")}}</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                    {{ __('Sign out') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
        @endif
    </div>

    @if($sidebar == '0')
        <a href="#" class="go-back-btn" onclick="history.back()"><i class="fa-sharp fa-solid fa-chevron-left"></i> {{__("Go Back")}}</a>
    @endif

    <div class="form d-flex justify-content-center align-items-center">
        <div class="flex justify-center pt-8 sm:justify-start sm:pt-0" id="lang-switcher">
            <a class="ml-1 underline ml-2 mr-2" href="/language/en">EN</a>
            <a class="ml-1 underline ml-2 mr-2" href="/language/mk">MK</a>
            <a class="ml-1 underline ml-2 mr-2" href="/language/sq">SQ</a>
        </div>
    </div>

</div>
<div class="content align-items-start {{$contentPart === 'homepage' ? 'with-bg' : ''}} {{!isset($sidebar) || $sidebar == 0 ? 'no-sidebar' : ''}}">
    @if($sidebar || $sidebar != '0')

    <aside class="collapse" id="mobile-menu">
        <ul>
            <li class="{{ $contentPart === 'homepage' ? "active" : "" }}">
                <a href="{{route('home')}}">{{__("Homepage")}}</a>
            </li>
            <li class="{{ $contentPart === 'customer' ? "active" : "" }}">
                <a href="{{route('customer')}}">{{__("Customer data")}}</a>
            </li>
            <li class="{{ $contentPart === 'invoices' ? "active" : "" }}">
                <a href="{{route('invoices')}}">{{__("Unpaid invoices")}}</a>
            </li>
            <li class="{{ $contentPart === 'all-invoices' ? "active" : "" }}">
                <!-- <a href="{{route('getAllInvoices')}}">{{__('Download invoices')}}</a> -->
                <a href="#" class="menu-disabled" data-toggle="tooltip"  data-placement="bottom" title="{{__('currently a pdf version of the invoice is not available. If you want to receive a pdf version of the invoice, ask us at 02 3088 500')}}">{{__('Download invoices')}}</a>
            </li>
            <li class="{{ $contentPart === 'transactions' ? "active" : "" }}">
                <a href="{{route('transactions')}}">{{__("Transactions list")}}</a>
            </li>
            <li class="{{ $contentPart === 'new_account' ? "active" : "" }}">
                <a href="{{route('accounts')}}">{{__("Add new customer")}}</a>
            </li>
            <li>
                <a href="{{route('information')}}">{{__("Help")}}</a>
            </li>
        </ul>
    </aside>
    @endif
    <content>
        @yield('content-part')
    </content>
</div>
<script>
    $(function () {
        if($('table').length > 0 ) {
            $('table').DataTable({
                paging: true,
                pageLength: 10,
                searching: true,
                processing: true,
                serverSide: false,
                ordering: false,
                responsive: true,
                pagingType: 'full_numbers',
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/{{Config::get('app.locale')}}.json',
                    paginate: {
                        first: '<i class="fa fa-fw fa-backward-step">',
                        next: '<i class="fa fa-fw fa-chevron-right">',
                        previous: '<i class="fa fa-fw fa-chevron-left">',
                        last: '<i class="fa fa-fw fa-forward-step">',
                    },
                },
                dom: '<"top"i>frt<"bottom"lp><"clear">',
            });
        }
    });
</script>
@endsection
