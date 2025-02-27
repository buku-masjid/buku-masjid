<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Facades\App\Helpers\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PublicShalatTimeController extends Controller
{
    public function show()
    {
        $cityName = Setting::get('masjid_city_name');
        $cities = $this->getAvailableCities();
        $cityId = array_search(strtolower($cityName), $cities);
        if ($cityId === false) {
            return response()->json([
                'error' => 'City not found.',
            ], 404);
        }

        try {
            $date = Carbon::now()->format('Y-m-d');
            $jadwalResponse = Http::get("https://api.myquran.com/v2/sholat/jadwal/{$cityId}/{$date}");
            $responseData = $this->parseResponseData($jadwalResponse->json());

            return $responseData;
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
        $shalatTimeProviderKey = config('shalat_time.default_provider');
        $baseUrl = config('shalat_time.providers.'.$shalatTimeProviderKey.'.api.base_url');
        $citiesEndpoint = config('shalat_time.providers.'.$shalatTimeProviderKey.'.api.cities_endpoint');
        $response = Http::get($baseUrl.$citiesEndpoint);
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

    private function parseResponseData(array $responseData): array
    {
        $schedules = $responseData['data']['jadwal'];
        if (array_key_exists('subuh', $schedules)) {
            $schedules['fajr'] = $schedules['subuh'];
            unset($schedules['subuh']);
        }
        if (array_key_exists('terbit', $schedules)) {
            $schedules['sunrise'] = $schedules['terbit'];
            unset($schedules['terbit']);
        }
        if (array_key_exists('dzuhur', $schedules)) {
            $schedules['dzuhr'] = $schedules['dzuhur'];
            unset($schedules['dzuhur']);
        }
        if (array_key_exists('ashar', $schedules)) {
            $schedules['ashr'] = $schedules['ashar'];
            unset($schedules['ashar']);
        }
        if (array_key_exists('tanggal', $schedules)) {
            $schedules['date_string'] = $schedules['tanggal'];
            unset($schedules['tanggal']);
        }
        asort($schedules);
        $responseData['data']['jadwal'] = $schedules;

        return $responseData['data'];
    }
}
