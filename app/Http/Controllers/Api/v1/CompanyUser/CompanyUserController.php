<?php

namespace App\Http\Controllers\Api\v1\CompanyUser;

use App\Actions\CompanyUser\CreateCompanyUser;
use App\Actions\CompanyUser\DeleteCompanyUser;
use App\Actions\CompanyUser\FetchCompanyUser;
use App\Actions\CompanyUser\GetCompanyUser;
use App\Actions\CompanyUser\UpdateCompanyUser;
use App\DTO\CompanyUser\CreateCompanyUserDTO;
use App\DTO\CompanyUser\UpdateCompanyUserDTO;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Api\v1\BaseController;
use App\Http\Requests\CompanyUser\CreateCompanyUserRequest;
use App\Http\Requests\CompanyUser\UpdateCompanyUserRequest;
use App\Http\Resources\CompanyUser\CompanyUserResource;
use App\Models\Company;
use App\Models\CompanyUser;
use Illuminate\Http\JsonResponse;

class CompanyUserController extends BaseController
{
    public function __construct()
    {
        $this->authorizeResource(CompanyUser::class, 'companyUser');
    }


    /**
     * Fetch all Employees
     */
    public function index(FetchCompanyUser $action): JsonResponse
    {
        $companyUsers = $action->execute();

        return ApiResponse::success(
            CompanyUserResource::collection($companyUsers),
            'Company users fetched successfully.'
        );
    }


    /**
     * Create Company Users
     */
    public function store(CreateCompanyUserRequest $request, Company $company, CreateCompanyUser $action): JsonResponse
    {
        $dto = CreateCompanyUserDTO::fromArray(
            $request->validated()
        );

        $companyUser = $action->execute($company, $dto);

        return ApiResponse::success(
            new CompanyUserResource($companyUser),
            'Company user created successfully.',
            201
        );
    }


    /**
     * Get Company User
     */
    public function show(CompanyUser $companyUser, GetCompanyUser $action): JsonResponse
    {
        return ApiResponse::success(
            new CompanyUserResource($action->execute($companyUser)),
            'Company user fetched successfully.'
        );
    }


    /**
     * Update Compny Users
     */
    public function update(
        UpdateCompanyUserRequest $request,
        CompanyUser $companyUser,
        UpdateCompanyUser $action,
    ): JsonResponse {
        $companyUser = $action->execute(
            $companyUser,
            UpdateCompanyUserDTO::fromArray($request->validated())
        );

        return ApiResponse::success(
            new CompanyUserResource($companyUser),
            'Company user updated successfully.'
        );
    }


    /**
     * Destroy Company Users
     */
    public function destroy(CompanyUser $companyUser, DeleteCompanyUser $action): JsonResponse
    {
        $action->execute($companyUser);

        return ApiResponse::success(
            null,
            'Company user deleted successfully.'
        );
    }
}
