<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalarySubmissionController;
use Illuminate\Support\Facades\Route;

Route::post('/submit', [SalarySubmissionController::class, 'store']);
Route::get('/submissions', [SalarySubmissionController::class, 'index']);
Route::get('/stats', [SalarySubmissionController::class, 'stats']);

Route::get('/companies', [CompanyController::class, 'index']);
Route::post('/companies', [CompanyController::class, 'createOrUpdate']);

Route::get('/roles', [RoleController::class, 'index']);
Route::post('/roles', [RoleController::class, 'createOrUpdate']);
