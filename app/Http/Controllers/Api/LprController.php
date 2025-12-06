<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Camera;
use App\Models\Detection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Artisan;
use App\Jobs\RunLprScan;
class LprController extends Controller
{
    // Fetch all cameras
    public function cameras()
    {
        return Camera::all();
    }

    // Fetch detections based on search or pagination
    public function detections(Request $request)
    {
        $query = Detection::with('camera')->orderByDesc('ts');

        // Live Feed: fetch the latest 10 detections
        if ($request->live) {
            // Trigger the scan command for new images
            Artisan::call('lpr:scan');
            return Detection::with('camera')
                            ->orderByDesc('ts')
                            ->limit(10)
                            ->get();
        }

        return $query->paginate(10); // Default pagination for normal view
    }

    // Download detection image
    public function download($id)
    {
        $d = Detection::findOrFail($id);
        return Response::download($d->file_path);
    }

    // Fetch live detections (new detections after last seen ID)
    public function live(Request $request)
        {
            $lastId = $request->after_id ?? 0;
            $camera = $request->camera; // Get the camera from the request

            // Dispatch the scan job to run asynchronously
            dispatch(new RunLprScan());

            $query = Detection::with('camera')
                ->where('id', '>', $lastId)
                ->orderBy('id')
                ->take(10);

            if ($camera && $camera !== 'all') {
                $query->whereHas('camera', function($query) use ($camera) {
                    $query->where('name', $camera);
                });
            }

            return $query->get();
        }
}
