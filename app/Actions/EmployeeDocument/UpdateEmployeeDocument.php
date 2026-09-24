<?php

namespace App\Actions\EmployeeDocument;

use App\DTO\Employee\UpdateEmployeeDocumentDTO;
use App\Models\EmployeeDocument;

class UpdateEmployeeDocument
{
    public function execute(EmployeeDocument $document, UpdateEmployeeDocumentDTO $dto): EmployeeDocument
    {
        $document->update([
            'document_type' => $dto->document_type,
            'document' => $dto->document,
        ]);

        return $document->refresh();
    }
}
