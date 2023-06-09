<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    /**
     * Get a listing of the partner.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return Partner::all();
    }

    /**
     * Store a newly created partner in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->authorize('create', new Partner);

        $newPartner = $request->validate([
            'name' => 'required|max:60',
            'description' => 'nullable|max:255',
        ]);
        $newPartner['creator_id'] = auth()->id();

        $partner = Partner::create($newPartner);

        return response()->json([
            'message' => __('partner.created'),
            'data' => $partner,
        ], 201);
    }

    /**
     * Update the specified partner in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Partner  $partner
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Partner $partner)
    {
        $this->authorize('update', $partner);

        $partnerData = $request->validate([
            'name' => 'required|max:60',
            'description' => 'nullable|max:255',
        ]);
        $partner->update($partnerData);

        return response()->json([
            'message' => __('partner.updated'),
            'data' => $partner,
        ]);
    }

    /**
     * Remove the specified partner from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Partner  $partner
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Partner $partner)
    {
        $this->authorize('delete', $partner);

        $partnerData = $request->validate(['partner_id' => 'required']);

        if ($partnerData['partner_id'] == $partner->id && $partner->delete()) {
            return response()->json(['message' => __('partner.deleted')]);
        }

        return response()->json('Unprocessable Entity.', 422);
    }
}
