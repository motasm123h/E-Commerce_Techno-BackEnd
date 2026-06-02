<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
   

    public function index()
    {
        $settings = Setting::all()->mapWithKeys(function ($setting) {
            return [$setting->key => $setting->getTranslations('value')];
        });

        return response()->json($settings, 200);
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($request->settings as $key => $value) {
            $setting = Setting::where('key', $key)->first();

            if ($setting) {
                if (is_array($value)) {
                    $setting->value = $value;
                } else {
                    $setting->setTranslation('value', 'en', $value);
                    $setting->setTranslation('value', 'ar', $value);
                }
                $setting->save();
            } else {
                $newSetting = new Setting();
                $newSetting->key = $key;
                if (is_array($value)) {
                    $newSetting->value = $value;
                } else {
                    $newSetting->setTranslation('value', 'en', $value);
                    $newSetting->setTranslation('value', 'ar', $value);
                }
                $newSetting->save();
            }
        }

        return response()->json(['message' => 'Settings updated successfully.'], 200);
    }
}
