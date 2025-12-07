<?php

namespace App\Http\Controllers;

use App\Models\Alert;

class AlertController extends Controller
{
    public function index()
    {
        $alerts = Alert::where('user_id', auth()->id())
                       ->latest()
                       ->get();

        return view('alerts.index', compact('alerts'));
    }
}
