<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PlatformSettingsController extends Controller
{
    public function index()
    {
        return view('platform.settings.index');
    }

    public function update(Request $request)
    {
        // placeholder for saving platform settings
        $data = $request->all();
        // implement saving logic
        return back()->with('success', 'Settings updated');
    }
}
