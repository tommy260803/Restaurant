<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class RolesYUsuariosSeeder extends Seeder
{
    public function run()
    {
        // Limpiar caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ========================================
        // CREAR ROLES DEL RESTAURANTE
        // ========================================
        $administrador = Role::firstOrCreate(['name' => 'administrador']);
        $cocinero = Role::firstOrCreate(['name' => 'cocinero']);
        $almacenero = Role::firstOrCreate(['name' => 'almacenero']);
        $cajero = Role::firstOrCreate(['name' => 'cajero']);

        // ========================================
        // CREAR PERMISOS
        // ========================================
        
        // Permisos de Administrador
        $permisosAdmin = [
            'ver_dashboard_admin',
            'gestionar_usuarios',
            'gestionar_trabajadores',
            'gestionar_roles',
            'gestionar_categorias',
            'gestionar_platos',
            'gestionar_proveedores',
            'ver_reportes',
            'configurar_sistema',
        ];

        // Permisos de Cocinero
        $permisosCocinero = [
            'ver_dashboard_cocina',
            'ver_pedidos',
            'preparar_pedidos',
            'completar_pedidos',
            'ver_recetas',
            'ver_ingredientes',
        ];

        // Permisos de Almacenero
        $permisosAlmacenero = [
            'ver_dashboard_almacen',
            'gestionar_ingredientes',
            'gestionar_compras',
            'ver_proveedores',
            'gestionar_inventario',
            'ver_movimientos',
            'generar_alertas_stock',
        ];

        // Permisos de Cajero
        $permisosCajero = [
            'ver_dashboard_caja',
            'realizar_ventas',
            'generar_comprobantes',
            'ver_comprobantes',
            'realizar_cierre_caja',
            'cobrar_efectivo',
            'cobrar_tarjeta',
        ];

        // Crear todos los permisos
        $todosLosPermisos = array_merge(
            $permisosAdmin, 
            $permisosCocinero, 
            $permisosAlmacenero, 
            $permisosCajero
        );

        foreach ($todosLosPermisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }

        // ========================================
        // ASIGNAR PERMISOS A ROLES
        // ========================================
        
        // Administrador tiene TODOS los permisos
        $administrador->syncPermissions($todosLosPermisos);

        // Cocinero solo sus permisos
        $cocinero->syncPermissions($permisosCocinero);

        // Almacenero solo sus permisos
        $almacenero->syncPermissions($permisosAlmacenero);

        // Cajero solo sus permisos
        $cajero->syncPermissions($permisosCajero);

        // ========================================
        // CREAR USUARIOS DE PRUEBA
        // ========================================

        // Usuario Administrador
        $admin = Usuario::firstOrCreate(
            ['email_mi_acta' => 'admin@restaurante.com'],
            [
                'dni_usuario' => 12345678,
                'nombre_usuario' => 'Administrador Sistema',
                'contrasena' => Hash::make('admin123'),
                'email_respaldo' => 'admin_backup@restaurante.com',
                'estado' => '1',
                'rol' => 'administrador',
            ]
        );
        $admin->assignRole('administrador');

        // Usuario Cocinero
        $chef = Usuario::firstOrCreate(
            ['email_mi_acta' => 'cocinero@restaurante.com'],
            [
                'dni_usuario' => 23456789,
                'nombre_usuario' => 'Carlos Pérez',
                'contrasena' => Hash::make('cocina123'),
                'email_respaldo' => 'carlos_backup@restaurante.com',
                'estado' => '1',
                'rol' => 'cocinero',
            ]
        );
        $chef->assignRole('cocinero');

        // Usuario Almacenero
        $almacen = Usuario::firstOrCreate(
            ['email_mi_acta' => 'almacenero@restaurante.com'],
            [
                'dni_usuario' => 34567890,
                'nombre_usuario' => 'María González',
                'contrasena' => Hash::make('almacen123'),
                'email_respaldo' => 'maria_backup@restaurante.com',
                'estado' => '1',
                'rol' => 'almacenero',
            ]
        );
        $almacen->assignRole('almacenero');

        // Usuario Cajero
        $caja = Usuario::firstOrCreate(
            ['email_mi_acta' => 'cajero@restaurante.com'],
            [
                'dni_usuario' => 45678901,
                'nombre_usuario' => 'Juan Rodríguez',
                'contrasena' => Hash::make('caja123'),
                'email_respaldo' => 'juan_backup@restaurante.com',
                'estado' => '1',
                'rol' => 'cajero',
            ]
        );
        $caja->assignRole('cajero');

        $this->command->info('✅ Roles, permisos y usuarios creados exitosamente!');
    }
}
