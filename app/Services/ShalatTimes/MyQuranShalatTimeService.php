<?php

namespace App\Services\ShalatTimes;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MyQuranShalatTimeService implements ShalatTimeService
{
    public function getSchedule(string $date)
    {
        $cities = $this->getAvailableCities();
        $cityName = config('shalat_time.providers.myquran_api.city_name');
        $cityId = array_search(strtolower($cityName), $cities);
        if ($cityId === false) {
            return [
                'error' => 'City not found.',
            ];
        }
        $baseUrl = config('shalat_time.providers.myquran_api.api_base_url');
        $jadwalResponse = Http::get($baseUrl."/sholat/jadwal/{$cityId}/{$date}");
        $responseData = $this->parseResponseData($jadwalResponse->json());

        return $responseData;
    }

    private function getAvailableCities()
    {
        $cacheKey = str_replace('\\', '', __METHOD__);
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        $baseUrl = config('shalat_time.providers.myquran_api.api_base_url');
        $response = Http::get($baseUrl.'/sholat/kota/semua');
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
        $responseData['data']['schedules'] = $schedules;
        unset($responseData['data']['jadwal']);
        $responseData['data']['location'] = $responseData['data']['lokasi'];
        unset($responseData['data']['lokasi']);
        $responseData['data']['region'] = $responseData['data']['daerah'];
        unset($responseData['data']['daerah']);

        return $responseData['data'];
    }
}
