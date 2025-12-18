<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\{Usuario, Persona, Administrador, Registrador, Region, Provincia, Distrito, Notificacion};
use Illuminate\Support\Facades\{Storage, Hash};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UsuarioController extends Controller
{
    const PAGINATION = 10;

    // ... MÉTODOS ANTERIORES SIN CAMBIOS ...

    /**
     * Actualizar datos del usuario (perfil, contraseña, imágenes)
     */
    public function update(Request $request, $id)
    {
        try {
            $usuario = Usuario::findOrFail($id);

            // Validar datos
            $request->validate([
                'nombre_usuario' => 'required|max:30|unique:usuarios,nombre_usuario,' . $usuario->id_usuario . ',id_usuario',
                'email_mi_acta' => 'required|email|unique:usuarios,email_mi_acta,' . $usuario->id_usuario . ',id_usuario',
                'email_respaldo' => 'nullable|email',
                'contrasena' => 'nullable|min:6',
                'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
                'portada' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            ], [
                'nombre_usuario.required' => 'El nombre de usuario es obligatorio.',
                'nombre_usuario.max' => 'El nombre no debe superar 30 caracteres.',
                'nombre_usuario.unique' => 'Este nombre de usuario ya existe.',
                'email_mi_acta.required' => 'El email es obligatorio.',
                'email_mi_acta.email' => 'El email debe ser válido.',
                'email_mi_acta.unique' => 'Este email ya está en uso.',
                'email_respaldo.email' => 'El email de respaldo debe ser válido.',
                'contrasena.min' => 'La contraseña mínimo 6 caracteres.',
                'foto.image' => 'Debe ser una imagen.',
                'foto.mimes' => 'Formatos permitidos: jpg, jpeg, png, webp.',
                'foto.max' => 'Máximo 3MB.',
                'portada.image' => 'Debe ser una imagen.',
                'portada.mimes' => 'Formatos permitidos: jpg, jpeg, png, webp.',
                'portada.max' => 'Máximo 5MB.',
            ]);

            // Actualizar datos básicos
            $usuario->nombre_usuario = $request->nombre_usuario;
            $usuario->email_mi_acta = $request->email_mi_acta;
            $usuario->email_respaldo = $request->email_respaldo;

            // Actualizar contraseña si se proporciona
            if ($request->filled('contrasena')) {
                $usuario->contrasena = Hash::make($request->contrasena);
            }

            // Manejo de foto de perfil
            if ($request->hasFile('foto')) {
                // Eliminar foto anterior si existe
                if ($usuario->foto && Storage::disk('public')->exists($usuario->foto)) {
                    Storage::disk('public')->delete($usuario->foto);
                }
                
                // Guardar nueva foto
                $fotoPath = $request->file('foto')->store('fotos_usuarios', 'public');
                $usuario->foto = $fotoPath;
            }

            // Manejo de portada
            if ($request->hasFile('portada')) {
                // Eliminar portada anterior si existe
                if ($usuario->portada && Storage::disk('public')->exists($usuario->portada)) {
                    Storage::disk('public')->delete($usuario->portada);
                }
                
                // Guardar nueva portada
                $portadaPath = $request->file('portada')->store('portadas_usuarios', 'public');
                $usuario->portada = $portadaPath;
            }

            // Guardar todos los cambios
            $usuario->save();

            return redirect()->route('usuarios.perfil', $usuario->id_usuario)
                           ->with('success', 'Perfil actualizado correctamente.');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error al actualizar perfil: ' . $e->getMessage(), ['user_id' => $id]);
            return back()->with('error', 'Error al actualizar. ' . $e->getMessage())->withInput();
        }
    }

    // ... RESTO DE MÉTODOS SIN CAMBIOS ...
}
