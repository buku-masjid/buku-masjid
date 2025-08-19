<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicDisplayController extends Controller
{
    public function index(Request $request)
    {
        $theme = $request->get('theme');
        if (!in_array($theme, config('public_display.available_themes'))) {
            $theme = config('public_display.theme');
        }

        return view('public_display.index', compact('theme'));
    }
}
