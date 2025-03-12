<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserSessionController extends Controller
{
    public function showForm(Request $request)
    {
        if (session('device_id')) {
            return view('userSessions.device-name', [
                'device_id' => session('device_id')
            ]);
        } else {
            return view('userSessions.device-name', [
                'device_id' => $request->device_id
            ]);
        }
    }

    public function store(Request $request)
    {    
        $request->validate([
            'device_name' => 'required|string|max:255'
        ]);

        $user = Auth::user();

        UserSession::create([
            'user_id' => $user->id,
            'device_id' => $request->cookie('device_id'),
            'token' => Str::random(60),
            'device_name' => $request->device_name,
            'ip_address' => $request->ip,
            'browser_device' => $request->browser,
            'last_activity' => now(),
        ]);

        return redirect()->route('principal');
    }

        public function manage(Request $request)
    {
        $user = Auth::user();

        // Guardar en la sesión la información del nuevo dispositivo
        $request->session()->put('device_id', $request->device_id);
    
        // Obtener los dispositivos del usuario
        $userSessions = UserSession::where('user_id', $user->id)->get();
    
        return view('userSessions.manage-devices', [
            'devices' => $userSessions,
            'device_id' => $request->device_id
        ]);
    }


    public function destroy(string $id, Request $request)
    {
        UserSession::where('id', $id)->delete();
        
        return redirect()->route('device.name.form');
    }
}
