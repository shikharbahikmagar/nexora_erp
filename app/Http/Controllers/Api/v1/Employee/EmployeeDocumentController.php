<?php

namespace App\Http\Controllers\Api\v1\Employee;

use App\Actions\EmployeeDocument\CreateEmployeeDocument;
use App\Actions\EmployeeDocument\FetchEmployeeDocuments;
use App\Actions\EmployeeDocument\UpdateEmployeeDocument;
use App\DTO\Employee\UpdateEmployeeDocumentDTO;
use App\DTO\Employee\CreateEmployeeDocumentDTO;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeDocument\CreateEmployeeDocumentRequest;
use App\Http\Requests\EmployeeDocument\UpdateEmployeeDocumentRequest;
use App\Models\Employee;
use App\Models\EmployeeDocument;
use Illuminate\Http\JsonResponse;

class EmployeeDocumentController extends Controller
{

    /**
     * Fetch Employee Document
     */

    public function index(Employee $employee, FetchEmployeeDocuments $action): JsonResponse
    {

        $documents = $action->execute($employee);

        return ApiResponse::success($documents, 'All Documents Fetched Successfully.');
    }


    /**
     * Fetch Employee Document
     */
    public function show(EmployeeDocument $document): JsonResponse
    {


        return ApiResponse::success($document, 'Document Fetched Successfully.');
    }


    /**
     * Create Employee Document
     */
    public function store(CreateEmployeeDocumentRequest $request, Employee $employee, CreateEmployeeDocument $action): JsonResponse
    {
        $dto = CreateEmployeeDocumentDTO::fromRequest($employee->id, $request->validated());

        $document = $action->execute($dto);

        return ApiResponse::success($document, 'Document Fetched Successfully');
    }

    /**
     * Update Employee Document
     */
    public function update(UpdateEmployeeDocumentRequest $request, EmployeeDocument $document, UpdateEmployeeDocument $action): EmployeeDocument
    {
        $dto = UpdateEmployeeDocumentDTO::fromRequest(
            $request->validated()
        );

        return $action->execute($document, $dto);
    }

    /**
     * Delete Employee Document
     */
    public function destroy(EmployeeDocument $document,): JsonResponse
    {
        $document->delete();

        return ApiResponse::success(null, 'Document Deleted Successfully.');
    }
}
