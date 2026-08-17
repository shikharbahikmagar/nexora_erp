<?php

namespace App\Http\Resources\Company;

use App\Http\Resources\Branch\BranchResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,

            'legal_name' => $this->legal_name,
            'registration_number' => $this->registration_number,
            'tax_number' => $this->tax_number,

            'contact' => [
                'email' => $this->email,
                'phone' => $this->phone,
                'website' => $this->website,
            ],

            'address' => [
                'address' => $this->address,
                'city' => $this->city,
                'state' => $this->state,
                'country' => $this->country,
                'postal_code' => $this->postal_code,
            ],

            'logo' => $this->logo,

            'is_active' => $this->is_active,

            'branches' => BranchResource::collection(
                $this->whenLoaded('branches')
            ),

            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
