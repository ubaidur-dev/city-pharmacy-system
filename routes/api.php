<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MedicineController;

Route::get('/medicines', [MedicineController::class, 'index']);
Route::get('/medicines/company/{company}', [MedicineController::class, 'getByCompany']); 
Route::get('/medicines/{id}', [MedicineController::class, 'show']);
Route::post('/medicines', [MedicineController::class, 'store']); 
Route::put('/medicines/{id}', [MedicineController::class, 'update']);
Route::patch('/medicines/{id}', [MedicineController::class, 'update']);
Route::delete('/medicines/{id}', [MedicineController::class, 'destroy']);