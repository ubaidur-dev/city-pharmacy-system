<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MedicineController;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\PurchasingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ComplianceController;
use App\Http\Controllers\StoreSettingController;

Route::post('/locale', function (\Illuminate\Http\Request $request) {
    $data = $request->validate(['locale' => ['required', \Illuminate\Validation\Rule::in(array_keys(config('locales')))]]);
    $request->session()->put('locale', $data['locale']);
    $request->user()?->update(['locale' => $data['locale']]);

    return back();
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1');
});

Route::middleware('auth')->group(function () {
    Route::get('/', fn () => redirect('/dashboard'));
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/admin/logout', [AuthController::class, 'logout']);

    Route::get('/search', SearchController::class);
    Route::get('/customers', [CustomerController::class, 'index']);
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->whereNumber('customer');
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->whereNumber('customer');
    Route::post('/pos/check', [PosController::class, 'check']);
    Route::get('/pos/lookup', [PosController::class, 'lookup']);
    Route::get('/pos/held', [PosController::class, 'held']);
    Route::post('/pos/hold', [PosController::class, 'hold']);
    Route::post('/pos/held/{bill}/resume', [PosController::class, 'resume']);
    Route::delete('/pos/held/{bill}', [PosController::class, 'discard']);
    Route::post('/sales-billing/orders/{order}/return', [ReturnController::class, 'store']);
    Route::get('/medicines/{medicine}/batches', [BatchController::class, 'index'])->whereNumber('medicine');
    Route::post('/medicines/{medicine}/batches', [BatchController::class, 'store'])->whereNumber('medicine');
    Route::get('/medicines', [MedicineController::class, 'indexView']);
    Route::post('/medicines/store', [MedicineController::class, 'storeView']);
    Route::put('/medicines/update/{id}', [MedicineController::class, 'updateView']);
    Route::get('/suppliers', [MedicineController::class, 'suppliersView']);
    Route::get('/stock-alerts', [MedicineController::class, 'stockAlertsView']);
    Route::get('/sales-billing', [MedicineController::class, 'salesBillingView']);
    Route::post('/sales-billing/generate', [MedicineController::class, 'generateBillView']);

    Route::middleware('role:owner')->group(function () {
        Route::delete('/medicines/{medicine}/batches/{batch}', [BatchController::class, 'destroy']);
        Route::get('/purchasing', [PurchasingController::class, 'index']);
        Route::post('/purchasing/suppliers', [PurchasingController::class, 'storeSupplier']);
        Route::get('/purchasing/suppliers/{supplier}', [PurchasingController::class, 'showSupplier']);
        Route::post('/purchasing/suppliers/{supplier}/payments', [PurchasingController::class, 'storePayment']);
        Route::get('/purchasing/orders/create', [PurchasingController::class, 'createOrder']);
        Route::post('/purchasing/orders', [PurchasingController::class, 'storeOrder']);
        Route::get('/purchasing/orders/{order}', [PurchasingController::class, 'showOrder']);
        Route::post('/purchasing/orders/{order}/receive', [PurchasingController::class, 'receive']);
        Route::post('/purchasing/orders/{order}/cancel', [PurchasingController::class, 'cancel']);

        Route::get('/compliance/controlled', [ComplianceController::class, 'controlled']);
        Route::get('/admin/audit', [ComplianceController::class, 'audit']);

        Route::get('/reports', [ReportController::class, 'index']);
        Route::get('/reports/export/{type}', [ReportController::class, 'export']);

        Route::get('/admin/store', [StoreSettingController::class, 'edit']);
        Route::put('/admin/store', [StoreSettingController::class, 'update']);
        Route::delete('/medicines/delete/{id}', [MedicineController::class, 'destroyView']);
        Route::get('/admin/profile', [MedicineController::class, 'profileView']);
        Route::put('/admin/profile/update', [MedicineController::class, 'updateProfile']);
        Route::put('/admin/password/update', [MedicineController::class, 'updatePassword']);
        Route::get('/admin/security', [MedicineController::class, 'securityView']);
        Route::put('/admin/security/email/update', [MedicineController::class, 'updateEmail']);
        Route::put('/admin/security/password/update', [MedicineController::class, 'updateSecurityPassword']);
        Route::get('/admin/theme-preferences', [MedicineController::class, 'themeView']);
        Route::put('/admin/theme/update', [MedicineController::class, 'updateTheme']);
    });
});
