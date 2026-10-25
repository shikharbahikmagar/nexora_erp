<?php

use App\Http\Controllers\Api\v1\Attendance\AttendanceController;
use App\Http\Controllers\Api\v1\Auth\AuthController;
use App\Http\Controllers\Api\v1\Branch\BranchController;
use App\Http\Controllers\Api\v1\Company\CompanyController;
use App\Http\Controllers\Api\v1\CompanyUser\CompanyUserController;
use App\Http\Controllers\Api\v1\Department\DepartmentController;
use App\Http\Controllers\Api\v1\Employee\EmployeeContractController;
use App\Http\Controllers\Api\v1\Employee\EmployeeController;
use App\Http\Controllers\Api\v1\Employee\EmployeeDocumentController;
use App\Http\Controllers\Api\v1\User\UserRoleController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::middleware(['cookie.auth', 'auth:sanctum'])->group(function () {

        // Get My Detail
        Route::get('/auth/me', [AuthController::class, 'me']);
        // Logout User
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        // Companies
        Route::get('/get-company', [CompanyController::class, 'index']);
        Route::get('/my-company', [CompanyController::class, 'myCompanyDetail']);
        Route::get('/company/{company}', [CompanyController::class, 'show']);
        Route::post('/create-company', [CompanyController::class, 'store']);
        Route::patch('/update-company/{company}', [CompanyController::class, 'update']);
        Route::delete('/delete-company/{company}', [CompanyController::class, 'destroy']);

        // Branch
        Route::get('/get-branches/{company}', [BranchController::class, 'index']);
        Route::get('/branch/{branch}', [BranchController::class, 'show']);
        Route::post('/create-branch', [BranchController::class, 'store']);
        Route::patch('/update-branch/{branch}', [BranchController::class, 'update']);
        Route::delete('/delete-branch/{branch}', [BranchController::class, 'destroy']);

        // Company users
        Route::get('company-users', [CompanyUserController::class, 'index']);
        Route::post('company-users/{company}', [CompanyUserController::class, 'store']);
        Route::get('company-users/{companyUser}', [CompanyUserController::class, 'show']);
        Route::patch('company-users/{companyUser}', [CompanyUserController::class, 'update']);
        Route::delete('company-users/{companyUser}', [CompanyUserController::class, 'destroy']);

        // Employees
        Route::get('employees', [EmployeeController::class, 'index']);
        Route::post('employees/{company}', [EmployeeController::class, 'store']);
        Route::get('employees/{employee}', [EmployeeController::class, 'show']);
        Route::patch('employees/{employee}', [EmployeeController::class, 'update']);
        Route::delete('employees/{employee}', [EmployeeController::class, 'destroy']);
        Route::post('/users/{user}/role', [UserRoleController::class, 'assign']);

        // Employee Documents
        Route::get('employee-documents/{employee}', [EmployeeDocumentController::class, 'index']);
        Route::post('employee-document/{employee}', [EmployeeDocumentController::class, 'store']);
        Route::get('employee-document/{document}', [EmployeeDocumentController::class, 'show']);
        Route::patch('employee-document/{document}', [EmployeeDocumentController::class, 'update']);
        Route::delete('employee-document/{document}', [EmployeeDocumentController::class, 'destroy']);

        // Employee Contract
        Route::get('employee-contract/{employee}', [EmployeeContractController::class, 'index']);
        Route::post('employee-contract/{employee}', [EmployeeContractController::class, 'store']);
        Route::get('employee-contract/{contract}', [EmployeeContractController::class, 'show']);
        Route::patch('employee-document/{contract}', [EmployeeContractController::class, 'update']);
        Route::delete('employee-document/{contract}', [EmployeeContractController::class, 'destroy']);

        // Attendance
        Route::get('attendances', [AttendanceController::class, 'index']);
        Route::post('attendances', [AttendanceController::class, 'store']);
        Route::get('attendances/{attendance}', [AttendanceController::class, 'show']);
        Route::patch('attendances/{attendance}', [AttendanceController::class, 'update']);
        Route::delete('attendances/{attendance}', [AttendanceController::class, 'destroy']);

        // Departments
        Route::get('departments/{company}', [DepartmentController::class, 'index']);
        Route::post('departments', [DepartmentController::class, 'store']);
        Route::get('departments/{department}', [DepartmentController::class, 'show']);
        Route::patch('departments/{department}', [DepartmentController::class, 'update']);
        Route::delete('departments/{department}', [DepartmentController::class, 'destroy']);
    });
});
