<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fullname' => $this->faker->name(),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'address' => $this->faker->address(),
            'date_of_birth' => $this->faker->date(),
            'telp_number' => $this->faker->phoneNumber(),
            'history' => $this->faker->paragraph(),
            'medical_record_file_url' => $this->faker->url(),
            'medical_record_number' => $this->faker->unique()->numerify('MRN#####'),
        ];
    }
}
