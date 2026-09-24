<?php

namespace App\DTO\Employee;

final readonly class UpdateEmployeeDocumentDTO
{
    public function __construct(
        public string $document_type,
        public string $document,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            document_type: $data['document_type'],
            document: $data['document'],
        );
    }
}
