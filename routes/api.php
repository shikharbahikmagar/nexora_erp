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
// Update company
Route::patch('/update-company/{company}', [CompanyController::class, 'update']);
// soft delete company
Route::delete('/delete-company/{company}', [CompanyController::class, 'destroy']);

// get all branches
Route::get('/get-branches', [BranchController::class, 'index']);
// get company Detail with branches
Route::get('/branch/{branch}', [BranchController::class, 'show']);
// Create branch
Route::post('/create-branch', [BranchController::class, 'store']);
// Update branch
Route::patch('/update-branch/{branch}', [BranchController::class, 'update']);
// soft delete branch
Route::delete('/delete-branch/{branch}', [BranchController::class, 'destroy']);
