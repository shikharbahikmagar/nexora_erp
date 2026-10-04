<?php

namespace App\Http\Controllers\Api\v1\Employee;

use App\Actions\EmployeeContract\CreateEmployeeContract;
use App\Actions\EmployeeContract\UpdateEmployeeContract;
use App\DTO\EmployeeContract\UpdateEmployeeContractDTO;
use App\DTO\EmployeeContract\CreateEmployeeContractDTO;
use App\Helpers\ApiResponse;
use App\Models\EmployeeContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeContract\UpdateEmployeeContractRequest;
use App\Http\Requests\EmployeeContract\CreateEmployeeContractRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;

class EmployeeContractController extends Controller
{

    /**
     * Fetch all Contract By Admin
     */

    public function index(Request $request, FetchEmployee $action): JsonResponse
    {
        $employees = $action->execute(
            user: $request->user(),
            search: $request->string('search')->toString(),
            perPage: $request->integer('per_page', 10),
        );

        return ApiResponse::success($employees, 'Employees fetched successfully.');
    }


    public function store(CreateEmployeeContractRequest $request, CreateEmployeeContract $action): JsonResponse
    {
        $dto = CreateEmployeeContractDTO::fromArray(
            $request->validated(),
            Auth::id()
        );

        $employeeContract = $action->execute($dto);

        return ApiResponse::success(
            $employeeContract,
            'Employee Contract Created Successfully.'
        );
    }

    /**
     * Update the specified employee contract.
     */
    public function update(UpdateEmployeeContractRequest $request, EmployeeContract $employeeContract, UpdateEmployeeContract $action): JsonResponse
    {
        $dto = UpdateEmployeeContractDTO::fromArray(
            $request->validated(),
            Auth::id()
        );

        $employeeContract = $action->execute($employeeContract, $dto);

        return ApiResponse::success(
            $employeeContract,
            'Employee Contract Updated Successfully.'
        );
    }
}
