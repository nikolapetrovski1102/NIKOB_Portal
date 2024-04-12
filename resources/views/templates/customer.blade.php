@extends('welcome', [
    'contentPart' => 'customer'
    ])

@section('content-part')
    @if(session('message'))
            <p class="alert alert-{{session('message')['type']}}">{{ session('message')['message'] }}</p>
    @endif
    <div class="card">
        <div class="card-header text-uppercase">{{ Auth::user()->name.' '.Auth::user()->surname }}</div>
        <div class="card-body p-4" style="width: 100%">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <h1 class="nikob-subtitle">{{__("Your informations")}}</h1>
                    <hr>
                    <form class="form" id="pay-fast-form" method="POST" action="{{route('customer')}}">
                        @csrf
                        <div class="form-group mb-2">
                            <label for="first_name">{{__("First Name")}}</label>
                            <input id="first_name" type="text" class="form-control with-border text-left" placeholder="{{ __('First Name') }}" value="{{ $customer['name'] }}" disabled>
                        </div>
                        
                        <div class="form-group mb-2">
                            <label for="last_name">{{__("Last Name")}}</label>
                            <input id="last_name" type="text" class="form-control with-border text-left" placeholder="{{ __('Last Name') }}" value="{{ $customer['surname'] }}" disabled>
                        </div>

                        <div class="form-group mb-4">
                            <label for="email_address">{{__("Email Address")}}</label>
                            <input id="email_address" type="text" class="form-control with-border text-left" placeholder="{{ __('Email Address') }}" name="email" value="{{ $customer['email'] }}">
                        </div>
                        <input type="hidden" name="action" value="change_email">
                        <div class="col-md-12 align-content-end">
                            <button type="submit" class="btn btn-primary text-capitalize d-inline-block w-auto">
                                {{__('Change Email')}}
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-md-6">
                    <h1 class="nikob-subtitle">{{__("Change password")}}</h1></h1>
                    <hr>
                    <form class="form" id="pay-fast-form" method="POST" action="{{route('customer')}}">
                        @csrf
                        <div class="form-group mb-2">
                            <label for="old_passworrd">{{__("Old password")}}</label>
                            <input id="old_passworrd" type="password" class="form-control with-border text-left" name="old_password">
                        </div>
                        
                        <div class="form-group mb-2">
                            <label for="new_password">{{__("New password")}}</label>
                            <input id="new_password" type="password" class="form-control with-border text-left" name="new_password">
                        </div>

                        <div class="form-group mb-4">
                            <label for="confirm_password">{{__("Confirm new password")}}</label>
                            <input id="confirm_password" type="password" class="form-control with-border text-left" name="confirm_password">
                        </div>
                        <input type="hidden" name="action" value="change_password">

                        <div class="col-md-12 align-content-end">
                            <button type="submit" class="btn btn-primary text-capitalize d-inline-block w-auto">
                                {{__('Change password')}}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
