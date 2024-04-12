<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\Implementations\CustomerService;

class RegisterInvoiceAccounts
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $customerService = new CustomerService();
        if (!$customerService->getRelatedAccounts()) {
            return redirect()->route('accounts');
        }
        return $next($request);
    }
}
