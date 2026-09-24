<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        $settings = SiteSetting::orderBy('group')->orderBy('id')->get()->groupBy('group');

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $values = $request->input('settings', []);

        foreach ($values as $key => $value) {
            if (is_array($value)) {
                $value = json_encode($value);
            }
            SiteSetting::where('key', $key)->first()?->update(['value' => $value]);
        }

        SiteSetting::flush();

        return back()->with('status', 'Website settings saved.');
    }
}
