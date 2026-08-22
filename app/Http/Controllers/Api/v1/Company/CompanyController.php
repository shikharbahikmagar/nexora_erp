<?php

namespace App\Http\Controllers\Api\v1\Company;

use App\Actions\Company\CreateCompany;
use App\Actions\Company\DeleteCompany;
use App\Actions\Company\FetchCompany;
use App\Actions\Company\GetCompany;
use App\Actions\Company\UpdateCompany;
use App\DTO\Company\CreateCompanyDTO;
use App\DTO\Company\UpdateCompanyDTO;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Api\v1\BaseController;
use App\Http\Requests\Company\CreateCompanyRequest;
use App\Http\Requests\Company\UpdateCompanyRequest;
use App\Http\Resources\Company\CompanyResource;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyController extends BaseController
{
    public function __construct()
    {
        $this->authorizeResource(Company::class, 'company');
    }


    /**
     * Fetch all companies
     */
    public function index(Request $request, FetchCompany $action): JsonResponse
    {
        $companies = $action->execute(
            search: $request->string('search')->toString(),
            perPage: $request->integer('per_page', 10),
        );

        return ApiResponse::success(
            CompanyResource::collection($companies),
            'Companies fetched successfully'
        );
    }

    /**
     * Create Company
     */
    public function store(CreateCompanyRequest $request, CreateCompany $action): JsonResponse
    {
        $dto = CreateCompanyDTO::fromArray($request->validated());

        $company = $action->execute($dto);

        return ApiResponse::success(
            new CompanyResource($company),
            'Company created successfully',
            201
        );
    }

    /**
     *Company Detail
     */
    public function show(int $company, GetCompany $action): JsonResponse
    {
        $company = $action->execute($company);

        return ApiResponse::success(
            new CompanyResource($company),
            'Company fetched successfully',
            201
        );
    }

    /**
     * Update Company
     */
    public function update(UpdateCompanyRequest $request, Company $company, UpdateCompany $action): JsonResponse
    {
        $dto = UpdateCompanyDTO::fromArray($request->validated());

        $company = $action->execute($company, $dto);

        return ApiResponse::success(
            new CompanyResource($company),
            'Company updated successfully'
        );
    }

    /**
     * Soft Delete Company
     */
    public function destroy(Company $company, DeleteCompany $action): JsonResponse
    {

        $deleted = $action->execute($company);

        $deleted = $action->execute($company);

        if (! $deleted) {
            return ApiResponse::success(
                null,
                'Company could not be deleted',
                500
            );
        }

        return ApiResponse::success(
            null,
            'Company deleted successfully'
        );
    }
}
