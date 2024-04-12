<?php

namespace App\Services\Contracts\Dynamics\Auth;

use GuzzleHttp\Client;

interface IAuthService
{
    public function authenticate(): object;
    public function authorize(): string;
    public function flushSession(): string;
}
