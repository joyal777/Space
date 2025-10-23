<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Models\GeneralSettings;

class GeneralSettingsController extends Controller
{
    public function index()
    {
        $settings = GeneralSettings::first();
        return Inertia::render('Front/GeneralSettings/Index', ['settings' => $settings]);
    }
}
