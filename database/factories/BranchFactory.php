<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Branch>
 */
class BranchFactory extends Factory
{
    protected $model = Branch::class;

    public function definition(): array
    {
        return [

            'name' => fake()->company() . ' Branch',
            'code' => fake()->unique()->bothify('BR-###'),

            'email' => fake()->unique()->companyEmail(),
            'phone' => fake()->phoneNumber(),

            'address' => fake()->address(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'country' => 'Nepal',
            'postal_code' => fake()->postcode(),

            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),

            'manager_name' => fake()->name(),

            'timezone' => 'Asia/Kathmandu',

            'is_head_office' => false,
            'is_active' => true,

            'description' => fake()->optional()->sentence(),
        ];
    }

    public function headOffice(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_head_office' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }
}
