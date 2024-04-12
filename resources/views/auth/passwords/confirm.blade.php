@extends('layouts.app')

@section('content')
<div class="dark">
    <div class="container p-3">
        <div class="row justify-content-center">
            <div class="col-md-4">

                {{ __('Please confirm your password before we continue') }}

                <form method="POST" action="{{ route('password.confirm') }}" class="form">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('Password') }}" name="password" required autocomplete="current-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-secondary">
                                {{ __('Confirm password') }}
                            </button>

                            @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot you password?') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
