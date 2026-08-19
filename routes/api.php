<?php

use App\Http\Controllers\Api\v1\Branch\BranchController;
use App\Http\Controllers\Api\v1\Company\CompanyController;
use Illuminate\Support\Facades\Route;

// get all companies with branches
Route::get('/get-company', [CompanyController::class, 'index']);
// get company Detail with branches
Route::get('/company/{company}', [CompanyController::class, 'show']);
// create company
Route::post('/create-company', [CompanyController::class, 'store']);
// create company
Route::patch('/update-company/{company}', [CompanyController::class, 'update']);
// soft delete company
Route::delete('/delete-company/{company}', [CompanyController::class, 'destroy']);

// routes for branches
Route::get('/get-branches', [BranchController::class, 'get']);
Route::post('/create-branch', [BranchController::class, 'store']);
