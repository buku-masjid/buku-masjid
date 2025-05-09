<?php

namespace App\Http\Controllers;

use App\Helpers\MapHelper;
use Facades\App\Helpers\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            'masjid_city_name' => 'required|string|max:255',
            'masjid_google_maps_link' => ['nullable', 'url', 'max:255'],
            'masjid_whatsapp_number' => ['nullable', 'alpha_num', 'max:255'],
            'masjid_instagram_username' => ['nullable', 'regex:/^@?(?!.*\.\.)(?!.*--)[a-zA-Z][a-zA-Z0-9._-]{4,31}$/', 'max:255'],
            'masjid_youtube_username' => ['nullable', 'regex:/^@?(?!.*\.\.)(?!.*--)[a-zA-Z][a-zA-Z0-9._-]{4,31}$/', 'max:255'],
            'masjid_facebook_username' => ['nullable', 'regex:/^@?(?!.*\.\.)(?!.*--)[a-zA-Z][a-zA-Z0-9._-]{4,31}$/', 'max:255'],
            'masjid_telegram_username' => ['nullable', 'regex:/^@?(?!.*\.\.)(?!.*--)[a-zA-Z][a-zA-Z0-9._-]{4,31}$/', 'max:255'],
        ]);

        Setting::set('masjid_name', $validatedPayload['masjid_name']);
        Setting::set('masjid_address', $validatedPayload['masjid_address']);
        Setting::set('masjid_city_name', $validatedPayload['masjid_city_name']);
        Setting::set('masjid_google_maps_link', $validatedPayload['masjid_google_maps_link']);
        Setting::set('masjid_whatsapp_number', $validatedPayload['masjid_whatsapp_number']);
        Setting::set('masjid_instagram_username', $validatedPayload['masjid_instagram_username']);
        Setting::set('masjid_youtube_username', $validatedPayload['masjid_youtube_username']);
        Setting::set('masjid_facebook_username', $validatedPayload['masjid_facebook_username']);
        Setting::set('masjid_telegram_username', $validatedPayload['masjid_telegram_username']);

        flash(__('masjid_profile.updated'), 'success');

        return redirect()->route('masjid_profile.show');
    }

    public function coordinatesUpdate(Request $request): RedirectResponse
    {
        $this->authorize('edit_masjid_profile');

        $validatedPayload = $request->validate([
            'google_maps_link' => 'required|string|max:255',
        ]);
        $coordinates = MapHelper::getCoordinatesFromGoogleMapsLink($validatedPayload['google_maps_link']);

        Setting::set('masjid_latitude', $coordinates['latitude'] ?? null);
        Setting::set('masjid_longitude', $coordinates['longitude'] ?? null);

        flash(__('masjid_profile.coordinate_updated'), 'success');

        return redirect()->route('masjid_profile.show');
    }
}
