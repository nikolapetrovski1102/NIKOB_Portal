@extends('layouts.app')

@section('content')
<div class="dark">
    <div class="container p-3">
        <div class="row justify-content-center">
            <div class="col-md-8 p-3">
                <form method="POST" action="{{ route('login') }}" class="form">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <input id="email" type="email" class="form-control text-center @error('email') is-invalid @enderror" placeholder="{{ __('Email Address') }}" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <input id="password" type="password" class="form-control text-center @error('password') is-invalid @enderror" placeholder="{{ __('Password') }}" name="password" required autocomplete="current-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                    <div class="col-md-12">
                            <button type="submit" class="btn btn-secondary">
                                {{ __('Log In') }}
                            </button>
                        </div>
                        
                    </div>

                    <div class="mb-0 d-flex justify-content-between">
                        <div>
                            <div class="form-check text-left">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label" for="remember">
                                    {{ __('Remember me') }}
                                </label>
                            </div>
                        </div>
                        @if (Route::has('password.request'))
                        <div>
                            <a class="btn btn-text" href="{{ route('password.request') }}">
                                {{ __('Forget password') }}
                            </a>
                        </div>
                        @endif
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                        {{ __('You don\'t have account?') }}
                        </div>

                        <div class="col-md-12">
                            <a href="{{ route('register') }}" class="btn btn-small">
                                {{ __('Create account') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
