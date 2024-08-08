<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Facades\App\Helpers\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BankAccountController extends Controller
{
    public function updateQrisImage(Request $request, BankAccount $bankAccount)
    {
        $this->authorize('update', $bankAccount);

        $validatedPayload = $request->validate([
            'image' => 'required',
        ]);

        if (!base64_decode($validatedPayload['image'])) {
            return response()->json([
                'message' => __('bank_account.image_not_found'),
            ]);
        }

        if ($qrisImagePath = Setting::for($bankAccount)->get('qris_image_path')) {
            Storage::delete($qrisImagePath);
        }

        $imageParts = explode(';base64,', $validatedPayload['image']);
        $imageBase64 = base64_decode($imageParts[1]);
        $imageName = uniqid().'.webp';

        Storage::put($imageName, $imageBase64);
        Setting::for($bankAccount)->set('qris_image_path', $imageName);

        return response()->json([
            'message' => __('bank_account.qris_image_uploaded'),
            'image' => Storage::url($imageName),
        ]);
    }
}
