<?php

namespace Tests\Unit\Helpers;

use App\Helpers\MapHelper;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MapHelperTest extends TestCase
{
    /** @test */
    public function it_extracts_coordinates_from_google_maps_link()
    {
        Http::fake([
            'https://maps.app.goo.gl/viUfQtHqjUXJHSLb8' => Http::response('', 302, [
                'Location' => 'https://www.google.com/maps/@40.7128,-74.0060,15z',
            ]),
        ]);

        $coordinates = MapHelper::getCoordinatesFromGoogleMapsLink('https://maps.app.goo.gl/viUfQtHqjUXJHSLb8');

        $this->assertEquals([
            'latitude' => '40.7128',
            'longitude' => '-74.0060',
        ], $coordinates);
    }

    /** @test */
    public function it_returns_null_for_invalid_google_maps_link()
    {
        Http::fake([
            'https://maps.app.goo.gl/invalid' => Http::response('', 404),
        ]);

        $coordinates = MapHelper::getCoordinatesFromGoogleMapsLink('https://maps.app.goo.gl/invalid');

        $this->assertNull($coordinates);
    }

    /** @test */
    public function it_returns_null_if_no_coordinates_are_found_in_url()
    {
        Http::fake([
            'https://maps.app.goo.gl/no-coordinates' => Http::response('', 302, [
                'Location' => 'https://www.google.com/maps/place/New+York',
            ]),
        ]);

        $coordinates = MapHelper::getCoordinatesFromGoogleMapsLink('https://maps.app.goo.gl/no-coordinates');

        $this->assertNull($coordinates);
    }
}
