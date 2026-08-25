<?php

namespace App\DTO\Employee;

class UpdateEmployeeDTO
{
    /** @param array<string, bool|int|string|null> $attributes */
    private function __construct(private readonly array $attributes) {}

    /** @param array<string, bool|int|string|null> $data */
    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    /** @return array<string, bool|int|string|null> */
    public function toArray(): array
    {
        return $this->attributes;
    }
}
