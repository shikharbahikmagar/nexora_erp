<?php

use App\Http\Controllers\Api\v1\Auth\AuthController;
use App\Http\Controllers\Api\v1\Branch\BranchController;
use App\Http\Controllers\Api\v1\Company\CompanyController;
use App\Http\Controllers\Api\v1\CompanyUser\CompanyUserController;
use App\Http\Controllers\Api\v1\Employee\EmployeeController;
use App\Http\Controllers\Api\v1\User\UserRoleController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {

        //Get My Detail
        Route::get('/auth/me', [AuthController::class, 'me']);
        //Logout User
        Route::post('/auth/logout', [AuthController::class, 'logout']);


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


        // Get all company users
        Route::get('company-users', [CompanyUserController::class, 'index']);

        // Add a user to a company
        Route::post('company-users/{company}', [CompanyUserController::class, 'store']);

        // Get a specific company user
        Route::get('company-users/{companyUser}', [CompanyUserController::class, 'show']);

        // Update a company user
        Route::patch('company-users/{companyUser}', [CompanyUserController::class, 'update']);

        // Remove a user from a company
        Route::delete('company-users/{companyUser}', [CompanyUserController::class, 'destroy']);


        // Get all employees
        Route::get('employees', [EmployeeController::class, 'index']);

        // Create an employee
        Route::post('employees/{company}', [EmployeeController::class, 'store']);

        // Get a specific employee
        Route::get('employees/{employee}', [EmployeeController::class, 'show']);

        // Update an employee
        Route::put('employees/{employee}', [EmployeeController::class, 'update']);

        // Partially update an employee
        Route::patch('employees/{employee}', [EmployeeController::class, 'update']);

        // Delete an employee
        Route::delete('employees/{employee}', [EmployeeController::class, 'destroy']);
        Route::post('/users/{user}/role', [UserRoleController::class, 'assign']);
    });
});
