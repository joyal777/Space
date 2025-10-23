<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\GeneralSettings;

class GeneralSettingsController extends Controller
{
    public function index()
    {
        $settings = GeneralSettings::first();
        // return Inertia::render('Admin/GeneralSettings/Index', ['settings' => $settings]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'favicon' => 'nullable|image|max:1024',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'tagline' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $settings = GeneralSettings::first() ?? new GeneralSettings;

        // Handle file uploads
        if ($request->hasFile('logo')) {
            $settings->logo = $request->file('logo')->store('logos', 'public');
        }
        if ($request->hasFile('favicon')) {
            $settings->favicon = $request->file('favicon')->store('favicons', 'public');
        }

        $settings->fill($request->except(['logo','favicon']));
        $settings->save();

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}

