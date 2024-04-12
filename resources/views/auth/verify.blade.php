@extends('layouts.app')

@section('content')
<div class="content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 m-4">
                <div class="card">
                    <div class="card-header">{{ __('Confirm your email address') }}</div>

                    <div class="card-body">
                        @if (session('message'))
                            <div class="alert alert-success" role="alert">
                                {{ __('We sent you new email to confirm your email') }}
                            </div>
                        @endif

                        {{ __('Before we continue please check you email for confirmation') }}
                        {{ __('If you do not receive the email') }},
                        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                            @csrf
                            <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to sent it again') }}</button>.
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
