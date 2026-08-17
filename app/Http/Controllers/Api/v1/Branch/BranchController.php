<?php

namespace App\Http\Controllers\Api\v1\Branch;

use App\Actions\Branch\FetchBranch;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function get(FetchBranch $action)
    {
        $resp = $action->execute();

        return ApiResponse::success($resp, 'Branches Fetched Successfully', 200);
    }
}
