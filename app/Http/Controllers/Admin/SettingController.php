<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index() {
        $settings = Setting::first();
        return view('admin.settings', compact('settings'));
    }

    public function update(Request $req) {
        $settings = Setting::first();

        if ($req->hasFile('logo')) {
            $path = $req->file('logo')->store('logos', 'public');
            $settings->logo_path = $path;
        }

        $settings->header_title = $req->header_title;
        $settings->save();

        return back()->with('success', 'Settings updated!');
    }
      public function getSettings()
    {
        // Fetch the first setting record from the database
        $settings = Setting::first(); 

        // Return the settings data as JSON
        return response()->json($settings);
    }
}
