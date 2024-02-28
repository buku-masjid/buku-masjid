<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Facades\App\Helpers\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MasjidProfileController extends Controller
{
    public function updateLogo(Request $request)
    {
        $this->authorize('edit_masjid_profile');

        $validatedPayload = $request->validate([
            'image' => 'required',
        ]);

        if (!base64_decode($validatedPayload['image'])) {
            return response()->json([
                'message' => __('masjid_profile.image_not_found'),
            ]);
        }

        if ($masjidLogoPath = Setting::get('masjid_logo_path')) {
            Storage::delete($masjidLogoPath);
        }

        $imageParts = explode(';base64,', $validatedPayload['image']);
        $imageBase64 = base64_decode($imageParts[1]);
        $imageName = uniqid().'.webp';

        Storage::put($imageName, $imageBase64);
        Setting::set('masjid_logo_path', $imageName);

        return response()->json([
            'message' => __('masjid_profile.logo_uploaded'),
            'image' => Storage::url($imageName),
        ]);
    }
}
