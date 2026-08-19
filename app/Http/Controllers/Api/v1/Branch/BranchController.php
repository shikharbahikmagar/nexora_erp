<?php

namespace App\Http\Controllers\Api\v1\Branch;

use App\Actions\Branch\CreateBranch;
use App\Actions\Branch\FetchBranch;
use App\DTO\Branch\CreateBranchDTO;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Branch\CreateBranchRequest;
use Illuminate\Http\JsonResponse;

class BranchController extends Controller
{
    public function get(FetchBranch $action): JsonResponse
    {
        $resp = $action->execute();

        return ApiResponse::success($resp, 'Branches Fetched Successfully', 200);
    }

    public function store(CreateBranchRequest $request, CreateBranch $action): JsonResponse
    {
        $dto = CreateBranchDTO::fromArray($request->validated());

        $resp = $action->execute($dto);

        return ApiResponse::success($resp, 'Company Created Successfully', 200);
    }
}
