<?php

namespace Database\Factories;

use App\Models\Departamento;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ci' => $this->faker->randomNumber(8, true),
            'usuario' => $this->faker->userName(),
            'password' => Hash::make('rodry'),
            'nombres' => $this->faker->word(),
            'paterno' => $this->faker->word(),
            'materno' => $this->faker->word(),
            'email'=>$this->faker->email(),                   
            'estado' => "activo",
            'cod_targeta' => $this->faker->randomNumber(9, true),
            'departamento_id'=>Departamento::inRandomOrder()->first(),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
    public function configure()
    {
        return $this->afterCreating(function (User $usuario) {
            $usuario->assignRole('estudiante'); // Asigna el rol 'Estudiante' al usuario creado
        });
    }
}
