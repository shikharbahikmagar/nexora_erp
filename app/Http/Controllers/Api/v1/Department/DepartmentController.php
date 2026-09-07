<?php

namespace App\Http\Controllers\Api\v1\Department;

use App\Actions\Department\CreateDepartment;
use App\Actions\Department\DeleteDepartment;
use App\Actions\Department\FetchDepartment;
use App\Actions\Department\GetDepartment;
use App\Actions\Department\UpdateDepartment;
use App\DTO\Department\CreateDepartmentDTO;
use App\DTO\Department\UpdateDepartmentDTO;
use App\Http\Controllers\Api\v1\BaseController;
use App\Http\Requests\Department\CreateDepartmentRequest;
use App\Http\Requests\Department\UpdateDepartmentRequest;
use App\Models\Department;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DepartmentController extends BaseController
{
    /**
     * Get All Departments
     */
    public function index(Request $request, int $company, FetchDepartment $action): JsonResponse
    {
        $resp = $action->execute(
            $company,
            search: $request->string('search')->toString(),
            perPage: $request->integer('per_page', 10),
        );

        return ApiResponse::success(
            $resp,
            'Departments Fetched Successfully.',
            200
        );
    }

    /**
     * Create Department
     */
    public function store(CreateDepartmentRequest $request, CreateDepartment $action): JsonResponse
    {
        $dto = new CreateDepartmentDTO(
            companyId: $request->integer('company_id'),
            name: $request->string('name')->toString(),
            code: $request->input('code'),
            description: $request->input('description'),
            isActive: $request->boolean('is_active', true),
        );

        $resp = $action->execute($dto);

        return ApiResponse::success(
            $resp,
            'Department Created Successfully.',
            201
        );
    }

    /**
     * Get Department
     */
    public function show(Department $department, GetDepartment $action): JsonResponse
    {
        $resp = $action->execute($department);

        return ApiResponse::success(
            $resp,
            'Department Fetched Successfully.',
            200
        );
    }

    /**
     * Update Department
     */
    public function update(UpdateDepartmentRequest $request, Department $department, UpdateDepartment $action): JsonResponse
    {
        $dto = new UpdateDepartmentDTO(
            name: $request->string('name')->toString(),
            code: $request->input('code'),
            description: $request->input('description'),
            isActive: $request->boolean('is_active', true),
        );

        $resp = $action->execute(
            department: $department,
            dto: $dto
        );

        return ApiResponse::success(
            $resp,
            'Department Updated Successfully.',
            200
        );
    }

    /**
     * Delete Department
     */
    public function destroy(Department $department, DeleteDepartment $action): JsonResponse
    {
        $action->execute($department);

        return ApiResponse::success(
            null,
            'Department Deleted Successfully.',
            200
        );
    }
}
