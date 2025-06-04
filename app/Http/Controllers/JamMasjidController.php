<?php

namespace App\Http\Controllers;

class JamMasjidController extends Controller
{
    public function index()
    {
        $theme = config('jam-masjid.theme');

        return view("jammasjid.themes.$theme.index");
    }
}
