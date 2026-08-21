<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HeartbeatController extends Controller
{
    /**
     * Menerima heartbeat dari ESP32.
     * POST /api/v1/heartbeat
     * Body: { "device_id": "R1", "ip": "192.168.x.x", "firmware_version": "1.0.0" }
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'device_id'        => 'required|string|max:10',
            'ip'               => 'nullable|string|max:15',
            'firmware_version' => 'nullable|string|max:20',
        ]);

        Device::updateOrCreate(
            ['device_id' => strtoupper($validated['device_id'])],
            [
                'ip_address'       => $validated['ip'] ?? null,
                'last_seen'        => now(),
                'firmware_version' => $validated['firmware_version'] ?? null,
            ]
        );

        return response()->json(['success' => true]);
    }
}
