<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string',
            'company_address' => 'required|string',
            'company_phone' => 'required|string',
            'tax_rate' => 'required|numeric',
            'currency' => 'required|string',
            'default_language' => 'required|string',
            'receipt_footer' => 'nullable|string',
            'low_stock_alert' => 'required|integer',
            'enable_email_notifications' => 'boolean'
        ]);

        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return back()->with('success', 'Settings updated successfully');
    }
}
