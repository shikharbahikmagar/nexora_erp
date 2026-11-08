<?php

namespace App\Http\Controllers\Api\v1\Employee;

use App\Actions\EmployeeContract\CreateEmployeeContract;
use App\Actions\EmployeeContract\FetchEmployeeContract;
use App\Actions\EmployeeContract\UpdateEmployeeContract;
use App\DTO\EmployeeContract\CreateEmployeeContractDTO;
use App\DTO\EmployeeContract\UpdateEmployeeContractDTO;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeContract\CreateEmployeeContractRequest;
use App\Http\Requests\EmployeeContract\UpdateEmployeeContractRequest;
use App\Models\Employee;
use App\Models\EmployeeContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeContractController extends Controller
{
    /**
     * Fetch all contracts for an employee.
     */
    public function index(Request $request, Employee $employee, FetchEmployeeContract $action): JsonResponse
    {
        $contracts = $action->execute(
            search: $request->string('search')->toString(),
            employeeId: $employee->id,
            perPage: $request->integer('per_page', 10),
        );

        return ApiResponse::success($contracts, 'Employee contracts fetched successfully.');
    }

    /**
     * Create an employee contract.
     */
    public function store(CreateEmployeeContractRequest $request, CreateEmployeeContract $action): JsonResponse
    {
        $dto = CreateEmployeeContractDTO::fromArray(
            $request->validated(),
            Auth::id()
        );

        $employeeContract = $action->execute($dto);

        return ApiResponse::success(
            $employeeContract->load(['employee', 'department', 'branch']),
            'Employee Contract Created Successfully.',
            201
        );
    }

    /**
     * Get an employee contract.
     */
    public function show(EmployeeContract $contract): JsonResponse
    {
        return ApiResponse::success(
            $contract->load(['employee', 'department', 'branch']),
            'Employee Contract Fetched Successfully.'
        );
    }

    /**
     * Update the specified employee contract.
     */
    public function update(UpdateEmployeeContractRequest $request, EmployeeContract $contract, UpdateEmployeeContract $action): JsonResponse
    {
        $dto = UpdateEmployeeContractDTO::fromArray(
            $request->validated(),
            Auth::id()
        );

        $employeeContract = $action->execute($contract, $dto);

        return ApiResponse::success(
            $employeeContract->load(['employee', 'department', 'branch']),
            'Employee Contract Updated Successfully.'
        );
    }

    /**
     * Delete the specified employee contract.
     */
    public function destroy(EmployeeContract $contract): JsonResponse
    {
        $contract->delete();

        return ApiResponse::success(
            null,
            'Employee Contract Deleted Successfully.'
        );
    }
}
