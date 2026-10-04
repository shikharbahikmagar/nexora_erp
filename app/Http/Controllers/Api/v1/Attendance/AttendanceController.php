<?php

namespace App\Http\Controllers\Api\v1\Attendance;

use App\Actions\Attendance\CreateAttendance;
use App\Actions\Attendance\DeleteAttendance;
use App\Actions\Attendance\FetchAttendance;
use App\Actions\Attendance\GetAttendance;
use App\Actions\Attendance\UpdateAttendance;
use App\DTO\Attendance\CreateAttendanceDTO;
use App\DTO\Attendance\UpdateAttendanceDTO;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Api\v1\BaseController;
use App\Http\Requests\Attendance\CreateAttendanceRequest;
use App\Http\Requests\Attendance\UpdateAttendanceRequest;
use App\Models\Attendance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttendanceController extends BaseController
{
    /**
     * Get All Attendances
     */
    public function index(Request $request, FetchAttendance $action): JsonResponse
    {
        $resp = $action->execute(
            employeeId: $request->integer('employee_id') ?: null,
            branchId: $request->integer('branch_id') ?: null,
            status: $request->string('status')->toString() ?: null,
            fromDate: $request->string('from_date')->toString() ?: null,
            toDate: $request->string('to_date')->toString() ?: null,
            search: $request->string('search')->toString() ?: null,
            perPage: $request->integer('per_page', 15),
        );

        return ApiResponse::success(
            $resp,
            'Attendances Fetched Successfully.',
            200
        );
    }

    /**
     * Create Attendance
     */
    public function store(CreateAttendanceRequest $request, CreateAttendance $action): JsonResponse
    {
        $dto = CreateAttendanceDTO::fromArray($request->validated());

        $resp = $action->execute($dto);

        return ApiResponse::success(
            $resp,
            'Attendance Created Successfully.',
            201
        );
    }

    /**
     * Get Attendance
     */
    public function show(Attendance $attendance, GetAttendance $action): JsonResponse
    {
        $resp = $action->execute($attendance);

        return ApiResponse::success(
            $resp,
            'Attendance Fetched Successfully.',
            200
        );
    }

    /**
     * Update Attendance
     */
    public function update(UpdateAttendanceRequest $request, Attendance $attendance, UpdateAttendance $action): JsonResponse
    {
        $dto = UpdateAttendanceDTO::fromArray($request->validated());

        $resp = $action->execute(
            attendance: $attendance,
            dto: $dto,
        );

        return ApiResponse::success(
            $resp,
            'Attendance Updated Successfully.',
            200
        );
    }

    /**
     * Delete Attendance
     */
    public function destroy(Attendance $attendance, DeleteAttendance $action): JsonResponse
    {
        $action->execute($attendance);

        return ApiResponse::success(
            null,
            'Attendance Deleted Successfully.',
            200
        );
    }
}
