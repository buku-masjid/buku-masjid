<?php

namespace App\Http\Controllers;

use Facades\App\Helpers\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MasjidProfileController extends Controller
{
    public function show(): View
    {
        return view('masjid_profile.show');
    }

    public function edit(): View
    {
        $this->authorize('edit_masjid_profile');

        return view('masjid_profile.edit');
    }

    public function update(Request $request): RedirectResponse
    {
        $this->authorize('edit_masjid_profile');

        $validatedPayload = $request->validate([
            'masjid_name' => 'required|string|max:255',
            'masjid_address' => 'required|string|max:255',
            'masjid_google_maps_link' => 'nullable|string|max:255',
        ]);

        Setting::set('masjid_name', $validatedPayload['masjid_name']);
        Setting::set('masjid_address', $validatedPayload['masjid_address']);
        Setting::set('masjid_google_maps_link', $validatedPayload['masjid_google_maps_link']);

        flash(__('masjid_profile.updated'), 'success');

        return redirect()->route('masjid_profile.show');
    }

    public function image(Request $request)
    {
        $this->authorize('edit_masjid_profile');

        $validatedPayload = $request->validate([
            'image' => 'required'
        ]);

        if (!base64_decode($validatedPayload['image'])) {
            return response()->json([
                'message' => 'Image not found'
            ]);
        }

        $image_parts      = explode(";base64,", $request->image);
        $image_base64     = base64_decode($image_parts[1]);
        $image_name       = uniqid() . '.webp';
        $image_full_path  = 'public/' . $image_name;

        Storage::put($image_full_path, $image_base64);
        Setting::set('masjid_image', $image_name);

        return response()->json([
            'message' => 'Image Uploaded Successfully',
            'image' => asset(Setting::get('masjid_image'))
        ]);
    }
}
