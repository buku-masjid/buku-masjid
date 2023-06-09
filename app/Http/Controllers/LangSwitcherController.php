<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LangSwitcherController extends Controller
{
    public function update(Request $request)
    {
        session(['lang' => $request->get('lang')]);

        return back();
    }
}
