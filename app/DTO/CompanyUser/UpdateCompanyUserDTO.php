<?php

namespace App\DTO\CompanyUser;

class UpdateCompanyUserDTO
{
    /**
     * @param  array<string, int|bool|null>  $attributes
     */
    private function __construct(
        private readonly array $attributes,
    ) {}

    /**
     * @param  array<string, int|bool|null>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    /**
     * @return array<string, int|bool|null>
     */
    public function toArray(): array
    {
        return $this->attributes;
    }
}
