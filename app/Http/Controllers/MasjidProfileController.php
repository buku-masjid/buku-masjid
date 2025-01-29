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
            'masjid_google_maps_link' => 'nullable|string|max:255',
        ]);

        Setting::set('masjid_name', $validatedPayload['masjid_name']);
        Setting::set('masjid_address', $validatedPayload['masjid_address']);
        Setting::set('masjid_city_name', $validatedPayload['masjid_city_name']);
        Setting::set('masjid_google_maps_link', $validatedPayload['masjid_google_maps_link']);

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

        Setting::set('masjid_latitude', $coordinates['latitude']);
        Setting::set('masjid_longitude', $coordinates['longitude']);

        flash(__('masjid_profile.coordinate_updated'), 'success');

        return redirect()->route('masjid_profile.show');
    }
}
