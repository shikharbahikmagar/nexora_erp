<?php

namespace App\Actions\EmployeeDocument;

use App\DTO\Employee\CreateEmployeeDocumentDTO;
use App\Models\EmployeeDocument;

class CreateEmployeeDocument
{

    public function execute(CreateEmployeeDocumentDTO $dto): EmployeeDocument
    {
        $document = EmployeeDocument::create([
            'employee_id' => $dto->employee_id,
            'document_type' => $dto->document_type,
            'document' => $dto->document,
        ]);

        return $document;
    }
}
