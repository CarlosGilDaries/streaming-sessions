<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActiveStream;
use App\Models\UserSession;
use Carbon\Carbon;

class ActiveStreamController extends Controller
{
    public function startStream(Request $request)
    {
        $user = Auth::user();
        $deviceId = $request->header('User-Device-Id');

        if (!$user || !$deviceId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $activeStreams = ActiveStream::where('user_id', $user->id)->count();
        $plan = $user->plan;
        $maxStreams = ($plan == "sencillo") ? 1 : 2;

        // Verificar si ya existe una instancia de transmisión activa para este usuario y dispositivo
        $existingStream = ActiveStream::where('user_id', $user->id)
            ->where('device_id', $deviceId)
            ->first();

        if ($existingStream) {
            return response()->json(['message' => 'El usuario ya está transmitiendo'], 200);
        }

        if ($activeStreams < $maxStreams) {
            ActiveStream::create([
                'user_id' => $user->id,
                'device_id' => $deviceId,
            ]);

            return response()->json(['message' => 'Transmisión iniciada correctamente.'], 200);
        }

        // Redirigir a la ruta streams-limit-reached si se supera el límite
        return response()->json([
            'error' => 'limit_reached',
            'redirect' => url('/streams-limit-reached')
        ], 403);
    }

    public function keepAlive(Request $request)
    {
        $user = Auth::user();
        $deviceId = $request->header('User-Device-Id');

        if (!$user || !$deviceId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        ActiveStream::where('user_id', $user->id)
            ->where('device_id', $deviceId)
            ->update(['last_active_at' => now()]);
        
        UserSession::where('user_id', $user->id)
            ->where('device_id', $deviceId)
            ->update(['last_activity' => now()]);

        return response()->json(['message' => 'Stream actualizado'], 200);
    }
}
