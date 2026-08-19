<?php

namespace App\Http\Controllers\Api\v1\Branch;

use App\Actions\Branch\CreateBranch;
use App\Actions\Branch\DeleteBranch;
use App\Actions\Branch\FetchBranch;
use App\Actions\Branch\GetBranch;
use App\Actions\Branch\UpdateBranch;
use App\DTO\Branch\CreateBranchDTO;
use App\DTO\Branch\UpdateBranchDTO;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Branch\CreateBranchRequest;
use App\Http\Requests\Branch\UpdateBranchRequest;
use App\Http\Resources\Branch\BranchResource;
use App\Models\Branch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BranchController extends Controller
{
    /**
     *Get All Branches
     */

    public function index(Request $request, FetchBranch $action): JsonResponse
    {
        $resp = $action->execute(
            search: $request->string('search')->toString(),
            perPage: $request->integer('per_page', 10),
        );

        return ApiResponse::success(
            $resp,
            'Branches Fetched Successfully',
            200
        );
    }

    /**
     *Create Branch
     */

    public function store(
        CreateBranchRequest $request,
        CreateBranch $action
    ): JsonResponse {
        $dto = CreateBranchDTO::fromArray($request->validated());

        $resp = $action->execute($dto);

        return ApiResponse::success(
            $resp,
            'Branch Created Successfully',
            201
        );
    }


    /**
     *Branch Detail
     */
    public function show(int $branch, GetBranch $action): JsonResponse
    {
        $branch = $action->execute($branch);

        return ApiResponse::success(
            new BranchResource($branch),
            'Branch fetched successfully',
            201
        );
    }


    /**
     *Update Branch Detail
     */

    public function update(UpdateBranchRequest $request, Branch $branch, UpdateBranch $action): JsonResponse
    {
        $dto = UpdateBranchDTO::fromArray($request->validated());

        $resp = $action->execute($branch, $dto);

        return ApiResponse::success(
            $resp,
            'Branch Updated Successfully',
            200
        );
    }

    /**
     *Soft Delete Branch
     */
    public function destroy(Branch $branch, DeleteBranch $action): JsonResponse
    {
        $action->execute($branch);

        return ApiResponse::success(
            null,
            'Branch Deleted Successfully',
            200
        );
    }
}
