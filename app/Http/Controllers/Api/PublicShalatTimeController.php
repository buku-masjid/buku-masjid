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

        $schedule = $shalatTimeService->getSchedule(Carbon::now()->format('Y-m-d'));
        $schedule = $this->adjustShalatTimeSchedule($schedule);
        try {

            return $schedule;
        } catch (Exception $e) {
            Log::error('Error fetching prayer times: '.$e->getMessage());

            return response()->json(['error' => 'Failed to fetch prayer times.'], 500);
        }
    }

    private function adjustShalatTimeSchedule(array $schedule): array
    {
        $shalatTimeAdjustmentConfig = config('shalat_time.adjustment_in_minutes');
        foreach ($shalatTimeAdjustmentConfig as $shalatTimeCode => $shalatTimeAdjustmentInMinutes) {
            if (is_null($shalatTimeAdjustmentInMinutes)) {
                continue;
            }
            $newSchedule = Carbon::parse($schedule['schedules'][$shalatTimeCode])->addMinutes($shalatTimeAdjustmentInMinutes);
            $schedule['schedules'][$shalatTimeCode] = $newSchedule->format('H:i');
            if ($shalatTimeCode == 'fajr' && is_null($shalatTimeAdjustmentConfig['imsak'])) {
                $schedule['schedules']['imsak'] = $newSchedule->subMinutes(10)->format('H:i');
            }
        }

        return $schedule;
    }
}
