@extends('layouts.app')

@section('content')
<div class="dark">
    <div class="container p-3">
        <div class="row justify-content-center">
            <div class="col-md-4">

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="form">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('Your email address') }}" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-secondary">
                                {{ __('Reset password') }}
                            </button>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                        {{ __('I know my password') }}
                        </div>

                        <div class="col-md-12">
                            <a href="{{ route('login') }}" class="btn btn-small">
                                {{ __('Log In') }}
                            </a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
