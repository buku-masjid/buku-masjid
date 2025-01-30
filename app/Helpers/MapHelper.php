<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class MapHelper
{
    public static function getCoordinatesFromGoogleMapsLink($shortUrl): ?array
    {
        $response = Http::withoutRedirecting()->get($shortUrl);
        $fullUrl = $response->header('Location');

        preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $fullUrl, $matches);

        if (count($matches) === 3) {
            return [
                'latitude' => $matches[1],
                'longitude' => $matches[2],
            ];
        }

        return null;
    }
}
