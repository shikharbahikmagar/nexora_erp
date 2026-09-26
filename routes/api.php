<?php

use App\Http\Controllers\Api\v1\Auth\AuthController;
use App\Http\Controllers\Api\v1\Branch\BranchController;
use App\Http\Controllers\Api\v1\Company\CompanyController;
use App\Http\Controllers\Api\v1\CompanyUser\CompanyUserController;
use App\Http\Controllers\Api\v1\Department\DepartmentController;
use App\Http\Controllers\Api\v1\Employee\EmployeeController;
use App\Http\Controllers\Api\v1\Employee\EmployeeDocumentController;
use App\Http\Controllers\Api\v1\User\UserRoleController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::middleware(['cookie.auth', 'auth:sanctum'])->group(function () {

        //Get My Detail
        Route::get('/auth/me', [AuthController::class, 'me']);
        //Logout User
        Route::post('/auth/logout', [AuthController::class, 'logout']);


        // get all companies with branches
        Route::get('/get-company', [CompanyController::class, 'index']);
        //get My Company
        Route::get('/my-company', [CompanyController::class, 'myCompanyDetail']);
        // get company Detail with branches
        Route::get('/company/{company}', [CompanyController::class, 'show']);
        // create company
        Route::post('/create-company', [CompanyController::class, 'store']);
        // Update company
        Route::patch('/update-company/{company}', [CompanyController::class, 'update']);
        // soft delete company
        Route::delete('/delete-company/{company}', [CompanyController::class, 'destroy']);

        // get all branches
        Route::get('/get-branches/{company}', [BranchController::class, 'index']);
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
        // Partially update an employee
        Route::patch('employees/{employee}', [EmployeeController::class, 'update']);
        // Delete an employee
        Route::delete('employees/{employee}', [EmployeeController::class, 'destroy']);
        Route::post('/users/{user}/role', [UserRoleController::class, 'assign']);

        //Employee Documents
        Route::get('employee-documents/{employee}', [EmployeeDocumentController::class, 'index']);
        Route::post('employee-document/{employee}', [EmployeeDocumentController::class, 'store']);
        Route::get('employee-document/{document}', [EmployeeDocumentController::class, 'show']);
        Route::patch('employee-document/{document}', [EmployeeDocumentController::class, 'update']);
        Route::delete('employee-document/{document}', [EmployeeDocumentController::class, 'destroy']);


        Route::get('departments/{company}', [DepartmentController::class, 'index']);
        Route::post('departments', [DepartmentController::class, 'store']);
        Route::get('departments/{department}', [DepartmentController::class, 'show']);
        Route::patch('departments/{department}', [DepartmentController::class, 'update']);
        Route::delete('departments/{department}', [DepartmentController::class, 'destroy']);
    });
});
