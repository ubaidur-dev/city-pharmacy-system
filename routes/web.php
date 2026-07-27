<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MedicineController;

Route::get('/medicines', [MedicineController::class, 'indexView']);
Route::post('/medicines/store', [MedicineController::class, 'storeView']);
Route::put('/medicines/update/{id}', [MedicineController::class, 'updateView']);
Route::delete('/medicines/delete/{id}', [MedicineController::class, 'destroyView']);
Route::get('/suppliers', [MedicineController::class, 'suppliersView']);
Route::post('/suppliers/store', [MedicineController::class, 'storeSupplierView']);
Route::get('/stock-alerts', [MedicineController::class, 'stockAlertsView']);
Route::get('/sales-billing', [MedicineController::class, 'salesBillingView']);
Route::post('/sales-billing/generate', [MedicineController::class, 'generateBillView']);