<?php

namespace App\DTO\Company;

class CreateCompanyDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $code,
        public readonly ?string $legalName = null,
        public readonly ?string $registrationNumber = null,
        public readonly ?string $taxNumber = null,
        public readonly ?string $email = null,
        public readonly ?string $phone = null,
        public readonly ?string $website = null,
        public readonly ?string $address = null,
        public readonly ?string $city = null,
        public readonly ?string $state = null,
        public readonly ?string $country = null,
        public readonly ?string $postalCode = null,
        public readonly ?string $logo = null,
        public readonly bool $isActive = true,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            code: $data['code'],
            legalName: $data['legal_name'] ?? null,
            registrationNumber: $data['registration_number'] ?? null,
            taxNumber: $data['tax_number'] ?? null,
            email: $data['email'] ?? null,
            phone: $data['phone'] ?? null,
            website: $data['website'] ?? null,
            address: $data['address'] ?? null,
            city: $data['city'] ?? null,
            state: $data['state'] ?? null,
            country: $data['country'] ?? null,
            postalCode: $data['postal_code'] ?? null,
            logo: $data['logo'] ?? null,
            isActive: $data['is_active'] ?? true,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'code' => $this->code,
            'legal_name' => $this->legalName,
            'registration_number' => $this->registrationNumber,
            'tax_number' => $this->taxNumber,
            'email' => $this->email,
            'phone' => $this->phone,
            'website' => $this->website,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'postal_code' => $this->postalCode,
            'logo' => $this->logo,
            'is_active' => $this->isActive,
        ];
    }
}
