<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Middleware to ensure only superadmin can access
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->check() && auth()->user()->role != 'superadmin') {
                return redirect('/');
            }
            return $next($request);
        });
    }

    // Show the admin panel
    public function index()
    {
        // If no settings exist, create a default one
        $settings = Setting::first();

        if (!$settings) {
            // Create default settings if they don't exist
            $settings = new Setting();
            $settings->header_title = 'Default Header Title';
            $settings->save();
        }

        return view('admin.dashboard', compact('settings'));
    }

    // Update settings
public function update(Request $request)
{
    // Validate only the required fields (header_title and logo)
    $request->validate([
        'header_title' => 'required|string|max:255',
        'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    // Fetch the first settings entry
    $setting = Setting::first();

    // Handle logo upload if it's present
    if ($request->hasFile('logo')) {
        // Store the logo file in the 'public' disk and get the path
        $logoPath = $request->file('logo')->store('logos', 'public');
        $setting->logo_path = $logoPath;
    }

    // Update the header title
    $setting->header_title = $request->header_title;

    // Save the changes to the settings
    $setting->save();

    // Redirect back with success message
    return redirect()->back()->with('success', 'Settings updated successfully.');
}

}
