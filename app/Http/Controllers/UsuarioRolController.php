<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Usuario, Persona, Administrador, Registrador, Region, Provincia, Distrito};
use Illuminate\Support\Facades\{Storage, Hash};

class UsuarioRolController extends Controller
{
    const PAGINATION = 10;

    public function index(Request $request)
    {
        $buscarpor = $request->get('buscarpor');
        $usuarios = Usuario::where('estado', '1')
            ->where('nombre_usuario', 'like', "%{$buscarpor}%")
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
        'dni_usuario' => 'required|exists:personas,dni|unique:usuarios,dni_usuario',
        'nombre_usuario' => 'required|max:30|unique:usuarios,nombre_usuario',
        'contrasena' => 'required|min:6',
        'email_mi_acta' => 'required|email|unique:usuarios,email_mi_acta',
        'email_respaldo' => 'nullable|email',
        'rol' => 'required|in:Administrador,Registrador',
    ], [
        'dni_usuario.required' => 'Debe seleccionar un DNI válido.',
        'dni_usuario.exists' => 'El DNI no está registrado en la tabla personas.',
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
        'rol' => $request->rol,
        'estado' => '1',
    ]);

    // Asignar rol usando Spatie (si estás usando Spatie\Permission)
    if (method_exists($usuario, 'assignRole')) {
        $usuario->assignRole($request->rol);
    }

    // Crear registro asociado en tabla de roles específicos
    if ($request->rol === 'Administrador') {
        \App\Models\Administrador::create([
            'id_usuario' => $usuario->id_usuario,
            'estado' => '1',
        ]);
    } elseif ($request->rol === 'Registrador') {
        \App\Models\Registrador::create([
            'id_usuario' => $usuario->id_usuario,
            'estado' => '1',
        ]);
    }

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
            'rol' => 'required|in:Administrador,Registrador',
            'contrasena' => 'nullable|min:6', // Opcional en edición
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'portada' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        $rolAnterior = $usuario->rol;
        
        // Actualizar campos básicos
        $usuario->nombre_usuario = $request->nombre_usuario;
        $usuario->email_mi_acta = $request->email_mi_acta;
        $usuario->email_respaldo = $request->email_respaldo;
        $usuario->rol = $request->rol;

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

        // Actualizar roles si cambió
        if ($rolAnterior !== $request->rol) {
            // Desactivar roles anteriores
            Administrador::where('id_usuario', $usuario->id_usuario)->update(['estado' => '0']);
            Registrador::where('id_usuario', $usuario->id_usuario)->update(['estado' => '0']);

            // Activar nuevo rol
            if ($request->rol === 'Administrador') {
                Administrador::updateOrCreate(
                    ['id_usuario' => $usuario->id_usuario], 
                    ['estado' => '1']
                );
            } elseif ($request->rol === 'Registrador') {
                Registrador::updateOrCreate(
                    ['id_usuario' => $usuario->id_usuario], 
                    ['estado' => '1']
                );
            }
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
        $request->session()->flush();
        return redirect('/login');
    }
}