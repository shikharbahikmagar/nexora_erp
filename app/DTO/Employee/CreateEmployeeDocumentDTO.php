<?php

namespace App\DTO\Employee;

final readonly class CreateEmployeeDocumentDTO
{
    public function __construct(
        public int $employee_id,
        public string $document_type,
        public string $document,
    ) {}

    public static function fromRequest(int $employeeId, array $data): self
    {
        return new self(
            employee_id: $employeeId,
            document_type: $data['document_type'],
            document: $data['document'],
        );
    }
}
