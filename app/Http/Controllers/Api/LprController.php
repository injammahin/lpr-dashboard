<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Camera;
use App\Models\Detection;
use App\Models\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Artisan;
use App\Jobs\RunLprScan;
use App\Events\AlertTriggered;
use App\Services\WhatsAppService;

class LprController extends Controller
{
    /**
     * Return all cameras
     */
    public function cameras()
    {
        return Camera::all();
    }


    /**
     * MAIN DETECTIONS — SEARCH + FILTER + PAGINATION
     */
    public function detections(Request $request)
    {
        $query = Detection::with('camera')->orderByDesc('ts');

        // Search
        if ($request->search) {
            $query->where('plate', 'LIKE', '%' . $request->search . '%');
        }

        // Camera filter
        if ($request->camera && $request->camera !== "all") {
            $query->whereHas('camera', function ($q) use ($request) {
                $q->where('name', $request->camera);
            });
        }

        // Normal paginated response for dashboard
        return $query->paginate(2);
    }



    /**
     * LIVE FEED — Returns only NEW detections after last ID
     * Removes duplicates + Shows only newly added items
     */
    public function live(Request $request)
    {
        $lastId = (int) ($request->after_id ?? 0);
        $camera = $request->camera ?? 'all';

        // Run async scan (does NOT block)
        dispatch(new RunLprScan());

        $query = Detection::with('camera')
            ->where('id', '>', $lastId)
            ->orderBy('id')
            ->limit(20);

        // Camera filtering
        if ($camera !== 'all') {
            $query->whereHas('camera', function ($q) use ($camera) {
                $q->where('name', $camera);
            });
        }

        $detections = $query->get();

        if ($detections->isEmpty()) {
            return [];
        }

        // Remove duplicate plates (live feed should show each plate only once)
        $unique = $detections->unique('plate')->values();

        // Check for alerts only on new plates
        foreach ($unique as $detection) {
            $this->checkForAlerts($detection);
        }

        return $unique;
    }



    /**
     * CHECK ALERT AGAINST A NEW DETECTION
     */
    public function checkForAlerts(Detection $detection)
    {
        // Match alert by plate
        $alert = Alert::where('plate', strtoupper($detection->plate))->first();
        if (!$alert) return;

        // Trigger broadcast popup
        event(new AlertTriggered($alert, $detection));

        // Build WhatsApp message
        $message = "🚨 *LPR Alert Triggered*\n" .
                   "Plate: {$detection->plate}\n" .
                   "Camera: {$detection->camera->name}\n" .
                   "Time: {$detection->ts}\n" .
                   "Triggered By: {$alert->user->name}";

        $image = asset('storage/' . $detection->file_path);

        // Send WhatsApp
        (new WhatsAppService)->sendToGroup($message, $image);
    }



    /**
     * Download detection image
     */
    public function download($id)
    {
        $d = Detection::findOrFail($id);
        return Response::download($d->file_path);
    }
}
