<?php

use App\Http\Controllers\Api\v1\Branch\BranchController;
use App\Http\Controllers\Api\v1\Company\CompanyController;
use Illuminate\Support\Facades\Route;

//routes for company
Route::get('/get-company', [CompanyController::class, 'get']);
Route::post('/create-company', [CompanyController::class, 'store']);

//routes for branches
Route::get('/get-branches', [BranchController::class, 'get']);
