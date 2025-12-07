<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    // Show all alerts
    public function index()
    {
        return Alert::with('user')->latest()->get();
    }

    // Store new alert
    public function store(Request $request)
    {
        $request->validate([
            'plate' => 'required|string',
            'camera_id' => 'nullable|exists:cameras,id',
            'send_whatsapp' => 'boolean'
        ]);

        $alert = Alert::create([
            'user_id' => auth()->id(),
            'plate' => strtoupper($request->plate),
            'camera_id' => $request->camera_id,
            'send_whatsapp' => $request->send_whatsapp ?? false,
        ]);

        return response()->json([
            'message' => 'Alert created successfully',
            'alert' => $alert
        ]);
    }

    // Delete an alert
    public function destroy($id)
    {
        $alert = Alert::findOrFail($id);

        if ($alert->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $alert->delete();

        return response()->json(['message' => 'Alert deleted']);
    }
}
