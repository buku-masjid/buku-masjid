<?php

namespace App\Http\Controllers;

class JamMasjidController extends Controller
{
    public function index()
    {
        $theme = env('JAMMASJID_THEME', 'default');

        return view("jammasjid.themes.$theme.index");
    }
}
