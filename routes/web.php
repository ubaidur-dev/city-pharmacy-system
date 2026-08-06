<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MedicineController;

Route::get('/medicines', [MedicineController::class, 'indexView']);
Route::post('/medicines/store', [MedicineController::class, 'storeView']);
Route::put('/medicines/update/{id}', [MedicineController::class, 'updateView']);
Route::delete('/medicines/delete/{id}', [MedicineController::class, 'destroyView']);

Route::get('/suppliers', [MedicineController::class, 'suppliersView']);
Route::get('/stock-alerts', [MedicineController::class, 'stockAlertsView']);
Route::get('/sales-billing', [MedicineController::class, 'salesBillingView']);
Route::post('/sales-billing/generate', [MedicineController::class, 'generateBillView']);

Route::get('/admin/profile', [MedicineController::class, 'profileView']);
Route::put('/admin/profile/update', [MedicineController::class, 'updateProfile']);
Route::put('/admin/password/update', [MedicineController::class, 'updatePassword']);

Route::get('/admin/security', [MedicineController::class, 'securityView']);
Route::put('/admin/security/email/update', [MedicineController::class, 'updateEmail']);
Route::put('/admin/security/password/update', [MedicineController::class, 'updateSecurityPassword']);
