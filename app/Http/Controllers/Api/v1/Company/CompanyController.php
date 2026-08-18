<?php

namespace App\Http\Controllers\Api\v1\Company;

use App\Actions\Company\CreateCompany;
use App\Actions\Company\FetchCompany;
use App\DTO\Company\CreateCompanyDTO;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Company\CreateCompanyRequest;
use App\Http\Resources\Company\CompanyResource;

class CompanyController extends Controller
{
    public function store(CreateCompanyRequest $request, CreateCompany $action)
    {
        $dto = CreateCompanyDTO::fromArray($request->validated());

        $resp = $action->execute($dto);

        return ApiResponse::success($resp, 'Company Created Successfully', 200);
    }

    public function get(FetchCompany $action)
    {
        $resp = $action->execute();

        return ApiResponse::success(CompanyResource::collection($resp), 'Company Fetched Successfully', 200);
    }
}
