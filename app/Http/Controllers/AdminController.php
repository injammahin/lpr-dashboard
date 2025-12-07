<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\InvitedUser;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserInvitationMail;
class AdminController extends Controller
{
    /**
     * Only Superadmin can access Admin Panel
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->check() && auth()->user()->role !== 'superadmin') {
                return redirect('/')->with('error', 'Access denied');
            }
            return $next($request);
        });
    }

    /**
     * Admin Dashboard
     */
    public function index()
    {
        $settings = Setting::first();

        // Create default settings if none exist
        if (!$settings) {
            $settings = Setting::create([
                'header_title' => 'Lakewood Shomrim LPR System',
                'logo_path' => null
            ]);
        }

        return view('admin.dashboard', compact('settings'));
    }

    /**
     * Update Settings (Title + Logo)
     */
    public function update(Request $request)
    {
        $request->validate([
            'header_title' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        $setting = Setting::first();

        // Upload logo if exists
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $setting->logo_path = $path;
        }

        $setting->header_title = $request->header_title;
        $setting->save();

        return back()->with('success', 'Settings updated successfully!');
    }

    /**
     * Invite Page (List invited users)
     */
 public function invitePage()
    {
        $invites = InvitedUser::orderBy('created_at', 'desc')->get();
        return view('admin.invite.index', compact('invites'));
    }

    public function invite(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        // Generate token
        $token = Str::random(40);

        // Save to DB
        $invite = InvitedUser::create([
            'email' => $request->email,
            'token' => $token,
            'invited_by' => auth()->id(),
            'accepted' => false
        ]);

        // Send email
        Mail::to($request->email)->send(new UserInvitationMail($invite));

        return back()->with('success', 'Invitation email sent successfully!');
    }
    /**
     * Store a New Invite
     */

}
