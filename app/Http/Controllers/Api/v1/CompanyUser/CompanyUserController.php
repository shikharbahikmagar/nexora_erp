<?php

namespace App\Http\Controllers\Api\v1\CompanyUser;

use App\Actions\CompanyUser\CreateCompanyUser;
use App\DTO\CompanyUser\CreateCompanyUserDTO;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Api\v1\BaseController;
use App\Http\Requests\CompanyUser\CreateCompanyUserRequest;
use App\Models\Company;
use App\Models\CompanyUser;
use Illuminate\Http\JsonResponse;

class CompanyUserController extends BaseController
{
    /* public function __construct() */
    /* { */
    /*     $this->authorizeResource(CompanyUser::class, 'companyuser'); */
    /* } */

    public function index(Company $company): JsonResponse
    {
        /* $companyUsers = CompanyUser::query() */
        /*     ->where('company_id', $company->id) */
        /*     ->with([ */
        /*         'user', */
        /*         'role', */
        /*     ]) */
        /*     ->get(); */
        /**/
        $companyUsers = CompanyUser::with('user', 'role', 'company')->get();

        return ApiResponse::success($companyUsers, 'Company Users Fetched Successfully.');
    }

    public function store(CreateCompanyUserRequest $request, Company $company, CreateCompanyUser $action): JsonResponse
    {
        $dto = CreateCompanyUserDTO::fromArray(
            $request->validated()
        );

        $companyUser = $action->execute($company, $dto);


        return ApiResponse::success($companyUser, 'Company Users Created Successfully.', 201);
    }
}
