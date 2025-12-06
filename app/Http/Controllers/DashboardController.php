<?php

namespace App\Http\Controllers;

use App\Models\Camera;
use App\Models\Detection;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $req)
    {
        // Load cameras for dropdown
        $cameras = Camera::orderBy('name')->get();

        // Base query
        $query = Detection::with('camera')->orderByDesc('ts');

        // Filter by camera
        if ($req->camera && $req->camera !== 'all') {
            $query->whereHas('camera', fn($q) =>
                $q->where('name', $req->camera)
            );
        }

        // Search by plate
        if ($req->search) {
            $query->where('plate', 'LIKE', '%' . $req->search . '%');
        }

        $detections = $query->paginate(20);

        return view('dashboard-ui.index', compact('cameras', 'detections'));
    }
}
