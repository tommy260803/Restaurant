<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Usuario, Persona, Administrador, Registrador, Region, Provincia, Distrito};
use Illuminate\Support\Facades\{Storage, Hash, Auth};

class UsuarioRolController extends Controller
{
    const PAGINATION = 10;

    // ========================================
    // MÉTODOS DE AUTENTICACIÓN
    // ========================================
    
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email_mi_acta' => 'required|email',
            'contrasena' => 'required'
        ], [
            'email_mi_acta.required' => 'El correo electrónico es obligatorio.',
            'email_mi_acta.email' => 'Debe ingresar un correo válido.',
            'contrasena.required' => 'La contraseña es obligatoria.',
        ]);

        // Intentar autenticación con las credenciales personalizadas
        $usuario = Usuario::where('email_mi_acta', $request->email_mi_acta)
            ->where('estado', '1')
            ->first();

        if ($usuario && Hash::check($request->contrasena, $usuario->contrasena)) {
            Auth::login($usuario, $request->filled('remember'));
            $request->session()->regenerate();
            
            // Verificar si el usuario tiene roles asignados
            if ($usuario->roles->isEmpty()) {
                Auth::logout();
                return back()->withErrors([
                    'email_mi_acta' => 'Este usuario no tiene un rol asignado.',
                ])->onlyInput('email_mi_acta');
            }

            // Obtener el primer rol del usuario
            $role = $usuario->roles->first();
            
            // Redirigir según el rol
            return match($role->name) {
                'administrador' => redirect()->route('admin.dashboard')
                    ->with('success', '¡Bienvenido Administrador!'),
                    
                'cocinero' => redirect()->route('cocina.dashboard')
                    ->with('success', '¡Bienvenido a la Cocina!'),
                    
                'almacenero' => redirect()->route('almacen.dashboard')
                    ->with('success', '¡Bienvenido al Almacén!'),
                    
                'cajero' => redirect()->route('caja.dashboard')
                    ->with('success', '¡Bienvenido a Caja!'),
                
                'mesero' => redirect()->route('mesero.dashboard')
                    ->with('success', '¡Bienvenido Mesero!'),
                
                'registrador' => redirect()->route('home')
                    ->with('success', '¡Bienvenido Registrador!'),
                    
                default => redirect()->route('home')
                    ->with('success', '¡Bienvenido!')
            };
        }

        return back()->withErrors([
            'email_mi_acta' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email_mi_acta');
    }

    // ========================================
    // GESTIÓN DE USUARIOS
    // ========================================

    public function index(Request $request)
    {
        $buscarpor = $request->get('buscarpor');
        $usuarios = Usuario::with('roles')
            ->where('estado', '1')
            ->when($buscarpor, function($query) use ($buscarpor) {
                return $query->where('nombre_usuario', 'like', "%{$buscarpor}%");
            })
            ->orderBy('id_usuario', 'desc')
            ->paginate(self::PAGINATION);

        return view('admin.usuario.index', compact('usuarios', 'buscarpor'));
    }

    public function create()
    {
        $personasConUsuario = Usuario::pluck('dni_usuario');
        $personas = Persona::where('estado', '1')
            ->whereNotIn('dni', $personasConUsuario)
            ->get();

        return view('admin.usuario.create', compact('personas'));
    }

    public function store(Request $request)
{
    $request->validate([
        'dni_usuario' => 'required|exists:persona,dni|unique:usuarios,dni_usuario',
        'nombre_usuario' => 'required|max:30|unique:usuarios,nombre_usuario',
        'contrasena' => 'required|min:6',
        'email_mi_acta' => 'required|email|unique:usuarios,email_mi_acta',
        'email_respaldo' => 'nullable|email',
        'rol' => 'required|in:administrador,cocinero,almacenero,cajero,mesero,registrador',
    ], [
        'dni_usuario.required' => 'Debe seleccionar un DNI válido.',
        'dni_usuario.exists' => 'El DNI no está registrado en la tabla persona.',
        'dni_usuario.unique' => 'Ya existe un usuario con este DNI.',
        'nombre_usuario.required' => 'El nombre de usuario es obligatorio.',
        'nombre_usuario.unique' => 'Este nombre de usuario ya está en uso.',
        'contrasena.required' => 'La contraseña es obligatoria.',
        'contrasena.min' => 'La contraseña debe tener al menos 6 caracteres.',
        'email_mi_acta.required' => 'Debe ingresar un correo electrónico.',
        'email_mi_acta.unique' => 'Este correo ya está registrado.',
        'email_respaldo.email' => 'El correo de respaldo debe ser válido.',
        'rol.required' => 'Debe seleccionar un rol.',
    ]);

    $usuario = Usuario::create([
        'dni_usuario' => $request->dni_usuario,
        'nombre_usuario' => $request->nombre_usuario,
        'contrasena' => bcrypt($request->contrasena),
        'email_mi_acta' => $request->email_mi_acta,
        'email_respaldo' => $request->email_respaldo,
        'estado' => '1',
    ]);

    // Asignar rol usando Spatie
    $usuario->assignRole($request->rol);

    return redirect()->route('usuarios.index')
                     ->with('datos', '✅ Usuario registrado correctamente.');
}


    public function edit($id)
    {
        // Corregido: variable en singular
        $usuario = Usuario::findOrFail($id);
        
        // Obtener personas disponibles (sin usuario) MÁS la persona actual del usuario
        $personasConUsuario = Usuario::where('id_usuario', '!=', $id)->pluck('dni_usuario');
        $personas = Persona::where('estado', '1')
            ->whereNotIn('dni', $personasConUsuario)
            ->get();

        // Crear una vista específica para edición o pasar flag
        return view('admin.usuario.edit', compact('usuario', 'personas'));
        // O si usas la misma vista:
        // return view('admin.usuario.create', compact('usuario', 'personas'));
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        // Validación mejorada
        $data = $request->validate([
            'nombre_usuario' => 'required|max:30|unique:usuarios,nombre_usuario,' . $id . ',id_usuario',
            'email_mi_acta' => 'required|email|unique:usuarios,email_mi_acta,' . $id . ',id_usuario',
            'email_respaldo' => 'nullable|email',
            'rol' => 'required|in:administrador,cocinero,almacenero,cajero,mesero,registrador',
            'contrasena' => 'nullable|min:6', // Opcional en edición
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'portada' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        $rolAnterior = $usuario->roles->first()?->name;
        
        // Actualizar campos básicos
        $usuario->nombre_usuario = $request->nombre_usuario;
        $usuario->email_mi_acta = $request->email_mi_acta;
        $usuario->email_respaldo = $request->email_respaldo;

        // Solo actualizar contraseña si se proporciona
        if ($request->filled('contrasena')) {
            $usuario->contrasena = Hash::make($request->contrasena);
        }

        // Manejo de foto
        if ($request->hasFile('foto')) {
            // Eliminar foto anterior si existe
            if ($usuario->foto) {
                Storage::disk('public')->delete($usuario->foto);
            }
            $usuario->foto = $request->file('foto')->store('fotos_usuarios', 'public');
        } elseif ($request->has('eliminar_foto')) {
            if ($usuario->foto) {
                Storage::disk('public')->delete($usuario->foto);
                $usuario->foto = null;
            }
        }

        // Manejo de portada
        if ($request->hasFile('portada')) {
            // Eliminar portada anterior si existe
            if ($usuario->portada) {
                Storage::disk('public')->delete($usuario->portada);
            }
            $usuario->portada = $request->file('portada')->store('portadas_usuarios', 'public');
        } elseif ($request->has('eliminar_portada')) {
            if ($usuario->portada) {
                Storage::disk('public')->delete($usuario->portada);
                $usuario->portada = null;
            }
        }

        $usuario->save();

        // Actualizar roles de Spatie si cambió
        if ($rolAnterior !== $request->rol) {
            // Remover roles anteriores y asignar el nuevo
            $usuario->syncRoles([$request->rol]);
        }

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    // PERFIL
    public function perfil($id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('admin.usuario.form-perfil.datosPerfil', compact('usuario'));
    }

    public function actualizarPerfil(Request $request, $id)
    {
        return $this->update($request, $id); // reutiliza lógica
    }

    // CUENTA
    public function cuenta($id)
    {
        $usuario = Usuario::with(['persona.distrito.provincia.region'])->findOrFail($id);

        return view('admin.usuario.form-perfil.datosCuenta', [
            'usuario' => $usuario,
            'persona' => $usuario->persona,
            'regiones' => Region::all(),
            'provincias' => Provincia::all(),
            'distritos' => Distrito::all()
        ]);
    }

    public function actualizarCuenta(Request $request, $id)
    {
        // Implementa según reglas y campos de Persona
        return back()->with('success', 'Cuenta actualizada.');
    }

    // NOTIFICACIONES
    public function notificaciones($id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('admin.usuario.form-perfil.datosNotificacion', ['usuario' => $usuario]);
    }

    public function actualizarNotificaciones(Request $request, $id)
    {
        // Implementa lógica según tus campos
        return back()->with('success', 'Preferencias de notificación actualizadas.');
    }

    public function confirmar($id)
    {
        $usuario = Usuario::findOrFail($id); // Corregido: singular
        return view('admin.usuario.confirmar', compact('usuario'));
    }

    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id); // Corregido: singular
        $usuario->estado = '0';
        $usuario->save();
        
        // También desactivar roles relacionados
        Administrador::where('id_usuario', $id)->update(['estado' => '0']);
        Registrador::where('id_usuario', $id)->update(['estado' => '0']);
        
        return redirect()->route('usuarios.index')->with('datos', 'Registro eliminado...!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')->with('success', 'Sesión cerrada correctamente');
    }
}