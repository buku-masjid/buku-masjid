<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ShalatTimes\ShalatTimeService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

class PublicShalatTimeController extends Controller
{
    public function show()
    {
        $shalatTimeService = app(ShalatTimeService::class);
        if (!$shalatTimeService) {
            Log::error('No Shalat Time provider configured.');

            return response()->json(['error' => 'No Shalat Time provider configured.'], 400);
        }

        try {
            return $shalatTimeService->getSchedule(Carbon::now()->format('Y-m-d'));
        } catch (Exception $e) {
            Log::error('Error fetching prayer times: '.$e->getMessage());

            return response()->json(['error' => 'Failed to fetch prayer times.'], 500);
        }
    }
}
