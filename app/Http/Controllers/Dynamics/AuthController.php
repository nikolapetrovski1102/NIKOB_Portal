<?php

namespace App\Http\Controllers\Dynamics;

use App\Http\Controllers\Controller;
use App\Services\Contracts\Dynamics\Auth\IAuthService;
class AuthController extends Controller
{
    private IAuthService $authService;

    public function __construct(IAuthService $authService)
    {

        $this->authService = $authService;
    }

    public function getAccessToken()
    {
        $this->authService->login();
    }
}
