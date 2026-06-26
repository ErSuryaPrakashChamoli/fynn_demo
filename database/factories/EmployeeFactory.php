<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
     protected $model = Employee::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       return [
            'emp_id' => 'EMP' . fake()->unique()->numberBetween(1000, 9999),

            'emp_name' => fake()->name(),

            'email' => fake()->unique()->safeEmail(),


            'designation' => fake()->randomElement([
                'Sales Executive',
                'Relationship Manager',
                'Team Leader',
                'Branch Manager',
                'Area Manager',
                'Operations Executive',
                'Credit Officer',
            ]),


            'doj' => fake()->date(),

            'reporting_date' => fake()->date(),

            'superviser_id' => null,

            'manager_id' => null,

            'cost_center' => 'CC-' . fake()->numberBetween(100, 999),

            'unit_name' => fake()->randomElement([
                'Delhi',
                'Noida',
                'Gurgaon',
                'Mumbai',
                'Pune',
                'Lucknow',
            ]),

        ];
    }
}
