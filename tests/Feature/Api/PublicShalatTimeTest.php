<?php

namespace Tests\Feature\Api;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PublicShalatTimeTest extends TestCase
{
    /** @test */
    public function visitor_can_get_public_shalat_time()
    {
        Http::fake([
            'api.myquran.com/*' => Http::sequence()
                ->push($this->getFakeCitiesResponse(), 200)
                ->push($this->getFakeShalatTimeResponse(), 200),
        ]);

        $this->getJson(route('api.public_shalat_time.show', 'Kab. Belitung'));

        $this->seeJsonStructure([
            'status',
            'request',
            'data' => [
                'id',
                'lokasi',
                'daerah',
                'jadwal' => [
                    'tanggal', 'imsak', 'subuh', 'terbit', 'dhuha', 'dzuhur', 'ashar', 'maghrib', 'isya', 'date',
                ],
            ],
        ]);
    }

    /** @test */
    public function visitor_get_error_when_city_is_not_found()
    {
        Http::fake([
            'api.myquran.com/*' => Http::sequence()
                ->push($this->getFakeCitiesResponse(), 200),
        ]);

        $this->getJson(route('api.public_shalat_time.show', 'Missing city name'));

        $this->seeJsonStructure([
            'status',
            'error',
        ]);
    }

    private function getFakeCitiesResponse()
    {
        return [
            'status' => true,
            'request' => [
                'path' => 'sholat/kota/semua',
            ],
            'data' => [
                ['id' => '0905', 'lokasi' => 'KAB. BELITUNG'],
                ['id' => '0906', 'lokasi' => 'KAB. BELITUNG TIMUR'],
            ],
        ];
    }

    private function getFakeShalatTimeResponse()
    {
        return [
            'status' => true,
            'request' => [
                'path' => '/sholat/jadwal/0905/2025-02-27',
                'year' => '2025',
                'month' => '02',
                'date' => '27',
            ],
            'data' => [
                'id' => 905,
                'lokasi' => 'KAB. BELITUNG',
                'daerah' => 'KEPULAUAN BANGKA BELITUNG',
                'jadwal' => [
                    'tanggal' => 'Kamis, 27/02/2025',
                    'imsak' => '04:32',
                    'subuh' => '04:42',
                    'terbit' => '05:54',
                    'dhuha' => '06:21',
                    'dzuhur' => '12:06',
                    'ashar' => '15:15',
                    'maghrib' => '18:10',
                    'isya' => '19:19',
                    'date' => '2025-02-27',
                ],
            ],
        ];
    }
}
