<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\UserSession;

class UserController extends Controller
{
    public function cambiarPlan(Request $request)
    {
        $user = Auth::user();
        $ip = getClientIp();
        $device_id = $request->cookie('device_id');
        $userAgent = $request->header('User-Agent');

        if ($user->plan == 'sencillo') {
            $user->plan = 'premium';
            $user->max_devices = 4;
            $maxDevices = 4;
        } else {
            $user->plan = 'sencillo';
            $user->max_devices = 2;
            $maxDevices = 2;
        }

        $user->save();

        $session = UserSession::where('user_id', $user->id)
        ->where('device_id', $device_id)
        ->where('ip_address', $ip)
        ->where('user_agent', $userAgent)
        ->first();

        if ($session) {
            $session->last_activity = now();
            $session->save();
        }

        $deviceCount = UserSession::where('user_id', $user->id)->count();

        // Si hay más sesiones que el nuevo límite, eliminar las más antiguas
        if ($deviceCount > $maxDevices) {
            // Obtener las sesiones más antiguas (ordenadas por last_activity)
            if ($deviceCount == 3) {
                $sessionsToDelete = UserSession::where('user_id', $user->id)
                    ->orderBy('last_activity', 'asc')
                    ->limit(1)
                    ->get();
            } else {
                $sessionsToDelete = UserSession::where('user_id', $user->id)
                ->orderBy('last_activity', 'asc')
                ->limit(2)
                ->get();
            }

            // Eliminar las sesiones más antiguas
            foreach ($sessionsToDelete as $sesion) {
                $sesion->delete();
            }
        }
        
        if ($user->plan == "premium" && !$session) {
            return redirect()->route('device.name.form')->with([
                'device_id' => $device_id,
                'ip' => $ip,
                'userAgent' => $userAgent,
            ]);
        }

        if ($session) {
            $session->last_activity = now();
            $session->save();
        }

        return redirect()->route('user');
    }
}

