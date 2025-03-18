<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserSession;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Artisan;


class LoginController extends Controller
{
    public function loginForm()
    {
        // Ejecuta el comando para crear usuarios predeterminados si es necesario
        Artisan::call('create:default-user');

        if (Auth::viaRemember()) {
            return redirect()->route('user');
        } else if (Auth::check()) {
            return redirect()->route('user');
        } else {
            return view('auth.login');
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        // Generar o recuperar el device_id de la cookie
        $device_id = $request->cookie('device_id');

        if (!$device_id) {
            // Si no existe un device_id, generar uno nuevo (UUID)
            $device_id = Str::uuid();
            // Almacenar el device_id en una cookie persistente
            Cookie::queue('device_id', $device_id, 60 * 24 * 365, null, null, false, true);  // Expira en 1 año
        }

        if (Auth::guard('web')->attempt($credentials, $remember)) {
            $user = Auth::user();
            $ip = getClientIp();
            $userAgent = $request->header('User-Agent');

            // Verificar si ya existe una sesión para este usuario desde este dispositivo/IP
            $session = UserSession::where('user_id', $user->id)
                ->where('device_id', $device_id)
                ->where('ip_address', $ip)
                ->where('user_agent', $userAgent)
                ->first();

            // Verificar la cantidad de dispositivos activos del usuario
            $plan = $user->plan;
            if ($plan == "sencillo") {
                $maxDevices = 2;
            } else {
                $maxDevices = 4;
            }
            $deviceCount = UserSession::where('user_id', $user->id)->count();

            // Si el usuario ha excedido el límite de dispositivos, redirigir para gestionar dispositivos
            if ((!$session) && ($deviceCount >= $maxDevices)) {
                // Generar una nueva cookie para respetar la unicidad
                $new_device_id = Str::uuid();
                Cookie::queue('device_id', $new_device_id, 60 * 24 * 365, null, null, false, true); 

                // Obtener los dispositivos registrados para el usuario
                $userSessions = UserSession::where('user_id', $user->id)->get();
                return redirect()->route('manage.devices')->with([
                    'devices' => $userSessions,
                    'device_id' => $new_device_id,
                    'ip' => $ip,
                    'userAgent' => $userAgent,
                ]);
            }
            if ((!$session) && ($deviceCount < $maxDevices)) {
                // Generar una nueva cookie para respetar la unicidad
                $new_device_id = Str::uuid();
                Cookie::queue('device_id', $new_device_id, 60 * 24 * 365, null, null, false, true); 

                return redirect()->route('device.name.form')->with([
                    'device_id' => $new_device_id,
                    'ip' => $ip,
                    'userAgent' => $userAgent,
                ]);
            }

            $request->session()->regenerate();
            $session->last_activity = now();
            $session->save();

            return redirect()->route('principal');
        }

        return redirect()->route('login')->withErrors(['password' => 'El email o la contraseña son incorrectos']);
    }

    public function logout(Request $request) {
        $session = UserSession::where('user_id', Auth::id())
            ->first();

        if ($session) {
            $session->last_activity = now();
            $session->save();
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('principal');
    }

    public function user() {
        return view('auth.user');
    }
}
