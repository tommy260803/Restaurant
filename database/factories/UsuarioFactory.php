<?php

namespace Database\Factories;

use App\Models\Usuario;
use App\Models\Persona;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UsuarioFactory extends Factory
{
    protected $model = Usuario::class;

    public function definition(): array
    {
        return [
            'dni_usuario' => Persona::factory()->create()->dni, // Asegura que exista persona
            'nombre_usuario' => $this->faker->userName(),
            'contrasena' => Hash::make('12345678'), // o Hash::make('password')
            'email_mi_acta' => $this->faker->unique()->safeEmail(),
            'email_respaldo' => $this->faker->unique()->safeEmail(),
            'rol' => $this->faker->randomElement(['Administrador', 'Registrador']),
            'estado' => 1,
            'foto' => null,
            'portada' => null,
        ];
    }
}
