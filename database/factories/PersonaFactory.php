<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PersonaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'dni' => $this->faker->unique()->numerify('########'),
            'nombres' => $this->faker->firstName(),
            'apellido_paterno' => $this->faker->lastName(),
            'apellido_materno' => $this->faker->lastName(),
            'sexo' => $this->faker->randomElement(['M', 'F']),
            'nacionalidad' => $this->faker->randomElement(['Peruana', 'Argentina', 'Chilena', 'Colombiana', 'Ecuatoriana']),
            'id_distrito' => '010101', // O algún código válido si tienes tabla ubigeo
            'estado_civil' => $this->faker->randomElement(['Soltero', 'Casado', 'Viudo', 'Divorciado']),
            'fecha_nacimiento' => $this->faker->date(),
            'estado' => 'A',
        ];
    }
}
