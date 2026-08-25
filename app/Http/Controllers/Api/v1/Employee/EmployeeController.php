<?php

namespace App\Http\Controllers\Api\v1\Employee;

use App\Actions\Employee\CreateEmployee;
use App\Actions\Employee\DeleteEmployee;
use App\Actions\Employee\FetchEmployee;
use App\Actions\Employee\GetEmployee;
use App\Actions\Employee\UpdateEmployee;
use App\DTO\Employee\CreateEmployeeDTO;
use App\DTO\Employee\UpdateEmployeeDTO;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Api\v1\BaseController;
use App\Http\Requests\Employee\CreateEmployeeRequest;
use App\Http\Requests\Employee\UpdateEmployeeRequest;
use App\Http\Resources\Employee\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeController extends BaseController
{
    public function __construct()
    {
        $this->authorizeResource(Employee::class, 'employee');
    }

    /**
     * Fetch all Company Users
     */

    public function index(Request $request, FetchEmployee $action): JsonResponse
    {
        $employees = $action->execute(
            user: $request->user(),
            search: $request->string('search')->toString(),
            perPage: $request->integer('per_page', 10),
        );

        return ApiResponse::success(EmployeeResource::collection($employees), 'Employees fetched successfully.');
    }

    public function store(CreateEmployeeRequest $request, CreateEmployee $action): JsonResponse
    {
        $employee = $action->execute(CreateEmployeeDTO::fromArray($request->validated()));

        return ApiResponse::success(new EmployeeResource($employee), 'Employee created successfully.', 201);
    }

    public function show(Employee $employee, GetEmployee $action): JsonResponse
    {
        return ApiResponse::success(new EmployeeResource($action->execute($employee)), 'Employee fetched successfully.');
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee, UpdateEmployee $action): JsonResponse
    {
        $employee = $action->execute($employee, UpdateEmployeeDTO::fromArray($request->validated()));

        return ApiResponse::success(new EmployeeResource($employee), 'Employee updated successfully.');
    }

    public function destroy(Employee $employee, DeleteEmployee $action): JsonResponse
    {
        $action->execute($employee);

        return ApiResponse::success(null, 'Employee deleted successfully.');
    }
}
