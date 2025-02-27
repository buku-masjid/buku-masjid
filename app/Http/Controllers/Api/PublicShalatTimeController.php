<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PublicShalatTimeController extends Controller
{
    public function show(string $cityName)
    {
        $cities = $this->getAvailableCities();
        $cityId = array_search(strtolower($cityName), $cities);
        if ($cityId === false) {
            return response()->json([
                'status' => false,
                'error' => 'City not found.',
            ], 404);
        }

        try {
            $date = Carbon::now()->format('Y-m-d');
            $jadwalResponse = Http::get("https://api.myquran.com/v2/sholat/jadwal/{$cityId}/{$date}");

            return $jadwalResponse->json();
        } catch (\Exception $e) {
            Log::error('Error fetching prayer times: '.$e->getMessage());
            return response()->json(['error' => 'Failed to fetch prayer times.'], 500);
        }
    }

    private function getAvailableCities()
    {
        $cacheKey = str_replace('\\', '', __METHOD__);
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        $citiesApiUrl = 'https://api.myquran.com/v2/sholat/kota/semua';
        $response = Http::get($citiesApiUrl);
        if ($response->successful()) {
            $responseData = $response->json()['data'];

            $cities = [];
            foreach ($responseData as $city) {
                $cities[$city['id']] = strtolower($city['lokasi']);
            }
        } else {
            Log::error('Error fetching cities: '.$response->status().' '.$response->body());
            $cities = [];
        }
        Cache::put($cacheKey, $cities, now()->addDay());

        return $cities;
    }
}
