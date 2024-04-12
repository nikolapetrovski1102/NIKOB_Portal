<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [App\Http\Controllers\IndexController::class, 'index'])->name('index');
Route::get('/information', [App\Http\Controllers\IndexController::class, 'information'])->name('information');
Route::post('/pay', [App\Http\Controllers\PayController::class, 'index'])->name('pay');
Route::get('/pay/callback/{status}', [App\Http\Controllers\PayController::class, 'callbackStatus'])->name('nestPayCallbackStatus');
// Route::post('/pay/callback', [App\Http\Controllers\PayController::class, 'callback'])->name('nestPayCallback');
Route::get('/e-invoice', [App\Http\Controllers\IndexController::class, 'eInvoice'])->name('eInvoice');
Route::post('/send-e-invoice', [App\Http\Controllers\IndexController::class, 'sendEInvoice'])->name('sendEInvoice');
Route::get('/pay/callback', [App\Http\Controllers\PayController::class, 'callback'])->name('nestPayCallbackGet');
Route::post('/pay/callback', [App\Http\Controllers\PayController::class, 'callback'])->name('nestPayCallback');

Route::get('/pay-fast', [App\Http\Controllers\PayFastController::class, 'index'])->name('payFastIndex');
Route::get('/pay-fast/status', [App\Http\Controllers\PayFastController::class, 'status'])->name('payFastStatus');
Route::get('/pay-fast/{action}', [App\Http\Controllers\PayFastController::class, 'index'])->name('payFastAction');
Route::post('/pay-fast/add-invoice', [App\Http\Controllers\PayFastController::class, 'init'])->name('addFastInvoice');
Route::post('/pay-fast/payment', [App\Http\Controllers\PayFastController::class, 'payment'])->name('initFastPayment');
Route::get('/pay-fast/delete/{id}', [App\Http\Controllers\PayFastController::class, 'delete'])->name('deleteFastInvoice');
Route::get('/pay/form/{orderID}', [App\Http\Controllers\PayController::class, 'form'])->name('payForm');

Route::get('language/{locale}', function ($locale) {
    app()->setLocale($locale);
    session()->put('locale', $locale);
    return redirect()->back();
});

Route::get('/email/verify', function () {
    return view('auth.verify');
})->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/home');
})->middleware(['signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1']);

Auth::routes(['verify' => true]);

Route::group(['middleware' => ['auth', 'verified', 'registerInvoiceAccounts']], function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/home/customer', [App\Http\Controllers\HomeController::class, 'customer'])->name('customer');
    Route::post('/home/customer', [App\Http\Controllers\HomeController::class, 'editCustomer']);
    Route::get('/home/invoices', [App\Http\Controllers\HomeController::class, 'invoices'])->name('invoices');
    Route::get('/home/all-invoices', [App\Http\Controllers\HomeController::class, 'getAllInvoices'])->name('getAllInvoices');
    Route::get('/home/invoices/download/{invoiceNumber}', [App\Http\Controllers\HomeController::class, 'downloadInvoice'])->name('downloadInvoice');
    Route::get('/home/transactions', [App\Http\Controllers\HomeController::class, 'transactions'])->name('transactions');
});

Route::post('/home/customer/accounts/connect', [App\Http\Controllers\HomeController::class, 'connectAccounts'])
    ->name('connectAccounts');

Route::get('/home/customer/accounts/remove', [App\Http\Controllers\HomeController::class, 'removeAccount'])
    ->name('removeAccount');

Route::get('/home/customer/accounts', [App\Http\Controllers\HomeController::class, 'accounts'])
    ->middleware('auth')
    ->name('accounts');

Route::get('/customers', [\App\Http\Controllers\Dynamics\FinOpsController::class, 'call'])->name('customers');

