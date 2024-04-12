<?php

namespace App\Providers;

use App\Services\Contracts\Dynamics\Auth\IAuthService;
use App\Services\Contracts\Dynamics\FinOps as FinOpsContr;
use App\Services\Implementations\Dynamics\FinOps as FinOpsImpl;
use App\Services\Implementations\Dynamics\Auth\ClientCredentialsAuth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use SaintSystems\OData\IODataClient;
use SaintSystems\OData\ODataClient;
use App\Support\Helpers\PhpRedis;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('PhpRedis', function ($app) {
            return new PhpRedis;
        });
        $this->app->bind(IAuthService::class, ClientCredentialsAuth::class);
        $this->app->bind(IODataClient::class, function () {
            return new ODataClient(Config::get('dynamics.resource_data'), function ($request) {
                $request->headers['Content-Type'] = 'application/json';
                $authService = new ClientCredentialsAuth();
                $accessToken = $authService->authorize();
                $request->headers['Authorization'] = "Bearer {$accessToken}";
            });
        });
        $this->app->bind(FinOpsContr\IFinOpsService::class, FinOpsImpl\FinOpsService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
