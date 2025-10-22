<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RolesYUsuariosSeeder extends Seeder
{
    public function run(): void
    {
        // Crear roles con nombres personalizados
        $adminRole = Role::firstOrCreate(['name' => 'Administrador']);
        $registradorRole = Role::firstOrCreate(['name' => 'Registrador']);

        // Crear usuario administrador
        $admin = Usuario::create([
            'dni_usuario' => '12345678',
            'nombre_usuario' => 'Administrador General',
            'contrasena' => Hash::make('admin123'),
            'email_mi_acta' => 'administrador@miacta.com',
            'email_respaldo' => 'admin_respaldo@miacta.com',
            'estado' => '1',
            'rol' => 'Administrador',
            'foto' => null,
            'portada' => null,
        ]);
        $admin->assignRole('Administrador');

        // Crear usuario registrador
        $registrador = Usuario::create([
            'dni_usuario' => '87654321',
            'nombre_usuario' => 'Registrador Uno',
            'contrasena' => Hash::make('registrador123'),
            'email_mi_acta' => 'registrador@miacta.com',
            'email_respaldo' => 'registrador_respaldo@miacta.com',
            'estado' => '1',
            'rol' => 'Registrador',
            'foto' => null,
            'portada' => null,
        ]);
        $registrador->assignRole('Registrador');

        $this->command->info('✅ Roles y usuarios iniciales creados con éxito.');
    }
}
