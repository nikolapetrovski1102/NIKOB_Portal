@extends('layouts.app')

@section('content')
<div class="dark">
    <div class="container m-3">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form method="POST" class="form" action="{{ route('register') }}">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6 ">
                            <input id="name" type="text" class="form-control text-center @error('name') is-invalid @enderror" placeholder="{{ __('First Name') }}" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control text-center @error('name') is-invalid @enderror" placeholder="{{ __('Last Name') }}" name="surname" value="{{ old('surname') }}" required autocomplete="name" autofocus>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                <select id="countryList" class="form-control" name="phone_prefix" required>    
                                    @foreach (getPhonePrefixes() as $prefix => $name)
                                        <option phonecode="{{ $prefix }}" 
                                                value="{{ $prefix }}" 
                                                id="shop-country" {{$prefix == "389" ? "selected" : ""}}>{{ $name }}
                                    </option>
                                    @endforeach
                                    </select>
                                </div>
                                <input id="phone" type="tel" class="form-control text-center telephone @error('phone') is-invalid @enderror" placeholder="{{ __('+3897XXXXXXX') }}" name="phone" required autocomplete="" type="tel">
                            </div>


                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <input id="email" type="email" class="form-control text-center @error('email') is-invalid @enderror" placeholder="{{ __('Email Address') }}" name="email" value="{{ old('email') }}" required autocomplete="email">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <input id="password" type="password" class="form-control text-center @error('password') is-invalid @enderror" placeholder="{{ __('Password') }}" name="password" required autocomplete="new-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div id="popover-password">
                                <p><span id="result"></span></p>
                                <div class="progress">
                                    <div id="password-strength" 
                                        class="progress-bar" 
                                        role="progressbar" 
                                        aria-valuenow="40" 
                                        aria-valuemin="0" 
                                        aria-valuemax="100" 
                                        style="width:0%">
                                    </div>
                                </div>
                                <ul class="password_tips">
                                    <li class="">
                                        <span class="low-upper-case">
                                            <i class="fas fa-circle" aria-hidden="true"></i>
                                            {{__("Lowercase & Uppercase")}}
                                        </span>
                                    </li>
                                    <li class="">
                                        <span class="one-number">
                                            <i class="fas fa-circle" aria-hidden="true"></i>
                                            {{__("Numbers")}} (0-9)
                                        </span> 
                                    </li>
                                    <li class="">
                                        <span class="one-special-char">
                                            <i class="fas fa-circle" aria-hidden="true"></i>
                                            {{__('Special Character')}} (!@#$%^&*)
                                        </span>
                                    </li>
                                    <li class="">
                                        <span class="eight-character">
                                            <i class="fas fa-circle" aria-hidden="true"></i>
                                           {{__("At least 8 Character")}}
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <input id="password-confirm" type="password" class="form-control text-center" placeholder="{{ __('Repeat password') }}" name="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-secondary">
                                {{ __('Create account') }}
                            </button>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                        {{ __('You already have an account') }}
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

<script>
    let state = false;
    let password = document.getElementById("password");
    let passwordStrength = document.getElementById("password-strength");
    let lowUpperCase = document.querySelector(".low-upper-case i");
    let number = document.querySelector(".one-number i");
    let specialChar = document.querySelector(".one-special-char i");
    let eightChar = document.querySelector(".eight-character i");

    password.addEventListener("keyup", function(){
        let pass = document.getElementById("password").value;
        checkStrength(pass);
    });

    function checkStrength(password) {
        let strength = 0;

        //If password contains both lower and uppercase characters
        if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) {
            strength += 1;
            lowUpperCase.classList.remove('fa-circle');
            lowUpperCase.classList.add('fa-check');
        } else {
            lowUpperCase.classList.add('fa-circle');
            lowUpperCase.classList.remove('fa-check');
        }
        //If it has numbers and characters
        if (password.match(/([0-9])/)) {
            strength += 1;
            number.classList.remove('fa-circle');
            number.classList.add('fa-check');
        } else {
            number.classList.add('fa-circle');
            number.classList.remove('fa-check');
        }
        //If it has one special character
        if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) {
            strength += 1;
            specialChar.classList.remove('fa-circle');
            specialChar.classList.add('fa-check');
        } else {
            specialChar.classList.add('fa-circle');
            specialChar.classList.remove('fa-check');
        }
        //If password is greater than 7
        if (password.length > 7) {
            strength += 1;
            eightChar.classList.remove('fa-circle');
            eightChar.classList.add('fa-check');
        } else {
            eightChar.classList.add('fa-circle');
            eightChar.classList.remove('fa-check');   
        }

        // If value is less than 2
        if (strength < 2) {
            passwordStrength.classList.remove('progress-bar-warning');
            passwordStrength.classList.remove('progress-bar-success');
            passwordStrength.classList.add('progress-bar-danger');
            passwordStrength.style = 'width: 10%';
        } else if (strength == 3) {
            passwordStrength.classList.remove('progress-bar-success');
            passwordStrength.classList.remove('progress-bar-danger');
            passwordStrength.classList.add('progress-bar-warning');
            passwordStrength.style = 'width: 60%';
        } else if (strength == 4) {
            passwordStrength.classList.remove('progress-bar-warning');
            passwordStrength.classList.remove('progress-bar-danger');
            passwordStrength.classList.add('progress-bar-success');
            passwordStrength.style = 'width: 100%';
        }
    }
</script>
@endsection

