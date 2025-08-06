<?php

namespace App\Http\Controllers;

class PublicDisplayController extends Controller
{
    public function index()
    {
        $theme = config('public_display.theme');

        return view("public_display.themes.$theme.index");
    }
}
