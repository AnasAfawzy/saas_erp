<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switch(Request $request)
    {
        $language = $request->input('language');

        // Validate language
        if (!in_array($language, ['ar', 'en'])) {
            $language = 'ar'; // Default to Arabic
        }

        // Set application locale
        App::setLocale($language);

        // Store language preference in session
        Session::put('locale', $language);

        return redirect()->back()->with('success', __('app.switch_language'));
    }

    public function current()
    {
        return response()->json([
            'current_locale' => App::getLocale(),
            'available_locales' => [
                'ar' => __('app.arabic'),
                'en' => __('app.english')
            ]
        ]);
    }
}
