<?php

namespace App\DTO\Branch;

class CreateBranchDTO
{
    public function __construct(
        public readonly int $company_id,
        public readonly string $name,
        public readonly string $code,
        public readonly ?string $email = null,
        public readonly ?string $phone = null,
        public readonly ?string $address = null,
        public readonly ?string $city = null,
        public readonly ?string $state = null,
        public readonly string $country = 'Nepal',
        public readonly ?string $postal_code = null,
        public readonly ?float $latitude = null,
        public readonly ?float $longitude = null,
        public readonly ?string $manager_name = null,
        public readonly string $timezone = 'Asia/Kathmandu',
        public readonly bool $is_head_office = false,
        public readonly bool $is_active = true,
        public readonly ?string $description = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            company_id: $data['company_id'],
            name: $data['name'],
            code: $data['code'],
            email: $data['email'] ?? null,
            phone: $data['phone'] ?? null,
            address: $data['address'] ?? null,
            city: $data['city'] ?? null,
            state: $data['state'] ?? null,
            country: $data['country'] ?? 'Nepal',
            postal_code: $data['postal_code'] ?? null,
            latitude: $data['latitude'] ?? null,
            longitude: $data['longitude'] ?? null,
            manager_name: $data['manager_name'] ?? null,
            timezone: $data['timezone'] ?? 'Asia/Kathmandu',
            is_head_office: $data['is_head_office'] ?? false,
            is_active: $data['is_active'] ?? true,
            description: $data['description'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'company_id' => $this->company_id,
            'name' => $this->name,
            'code' => $this->code,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'postal_code' => $this->postal_code,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'manager_name' => $this->manager_name,
            'timezone' => $this->timezone,
            'is_head_office' => $this->is_head_office,
            'is_active' => $this->is_active,
            'description' => $this->description,
        ];
    }
}
