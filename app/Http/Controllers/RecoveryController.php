<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\CodigoRecuperacion;
use Illuminate\Support\Facades\Hash;

class RecoveryController extends Controller
{
    public function step1()
    {
        return view('auth.recovery1');
    }

    public function verifyMiActa(Request $request) {

        $email_mi_acta = $request->input('email_mi_acta');

        session(['email_mi_acta' => $email_mi_acta]);

        $data = $request->validate([
            'email_mi_acta' => 'required|email',
        ],
        [
            'email_mi_acta.required' => 'Ingrese su correo',
            'email_mi_acta.email' => 'Ingrese un correo válido',
        ]);

        $user = Usuario::where('email_mi_acta', '=', $email_mi_acta)->first();

        if ($user) {
            return redirect()->route('recovery.step2');
        } else {
            return back()->withErrors(['email_mi_acta' => 'Correo no registrado'])
                        ->withInput($request->only('email_mi_acta'));
        }
    }

    public function step2()
    {
        $email_mi_acta = session('email_mi_acta');

        return view('auth.recovery2', compact('email_mi_acta'));
    }

    public function sendCode(Request $request)
    {
        $codigo = substr(str_shuffle('0123456789'), 0, 6);

        $gmail = $request->input('email');

        session(['recovery_gmail' => $gmail]);
        session(['recovery_code' => $codigo]);

        $data = $request->validate([
            'email' => 'required|email',
        ],
        [
            'email.required' => 'Ingrese su correo electrónico',
            'email.email' => 'Ingrese un correo válido',
        ]);

        $user = Usuario::where('email_respaldo', '=', $gmail)->first();

        if ($user) {
            Mail::to($request->email)->send(new CodigoRecuperacion($codigo));
            return redirect()->route('recovery.step3');
        } else {
            return back()->withErrors(['email' => 'Correo de recuperación incorrecto'])
                        ->withInput($request->only('email'));
        }
    }

    public function step3()
    {
        $gmail = session('recovery_gmail');
        
        $email_mi_acta = session('email_mi_acta');

        return view('auth.recovery3', compact('gmail', 'email_mi_acta'));
    }

    public function verifyCode(Request $request)
    {
        $codeDigits = $request->input('code');

        $fullCode = implode('', $codeDigits);

        if ($fullCode === session('recovery_code')) {
            return redirect()->route('recovery.step4');
        } else {
            if ($fullCode == '') {
                return back()->withErrors(['code' => 'Ingrese el código de verificación'])
                            ->withInput($request->only('code'));
            } else {
                return back()->withErrors(['code' => 'Código incorrecto'])
                        ->withInput($request->only('code'));
            }
        }
    }

    public function step4()
    {   
        $email_mi_acta = session('email_mi_acta');

        return view('auth.recovery4', compact('email_mi_acta'));
    }
    
    public function changeCode(Request $request)
    {
        $request->validate([
            'password1' => 'required|min:6',
            'password2' => 'required|same:password1',
        ], [
            'password1.required' => 'Ingrese una nueva contraseña',
            'password1.min' => 'La contraseña debe tener al menos 6 caracteres',
            'password2.required' => 'Confirme su contraseña',
            'password2.same' => 'Las contraseñas no coinciden',
        ]);

        $email = session('email_mi_acta');

        $user = Usuario::where('email_mi_acta', $email)->first();

        $user->contrasena = Hash::make($request->input('password1'));
        $user->save();

        session()->forget('email_mi_acta');

        return redirect()->route('login');
    }
}
