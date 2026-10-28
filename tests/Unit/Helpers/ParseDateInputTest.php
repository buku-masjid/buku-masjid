<?php

namespace Tests\Unit\Helpers;

use Carbon\Carbon;
use Tests\TestCase;

class ParseDateInputTest extends TestCase
{
    /** @test */
    public function it_returns_the_date_for_a_valid_y_m_d_string(): void
    {
        $date = parse_date_input('2026-10-05');

        $this->assertInstanceOf(Carbon::class, $date);
        $this->assertEquals('2026-10-05 00:00:00', $date->format('Y-m-d H:i:s'));
    }

    /** @test */
    public function it_trims_surrounding_whitespace(): void
    {
        $this->assertEquals('2026-10-05', parse_date_input(' 2026-10-05 ')->format('Y-m-d'));
    }

    /** @test */
    public function it_accepts_a_real_leap_day(): void
    {
        $this->assertEquals('2028-02-29', parse_date_input('2028-02-29')->format('Y-m-d'));
    }

    /** @test */
    public function it_returns_null_for_values_that_are_not_a_real_y_m_d_date(): void
    {
        $invalidValues = [
            null, '', '   ', 0, 20261005, ['2026-10-05'],
            '2026-02-30', '2026-02-29', '2026-04-31', '2026-13-45', '0000-00-00',
            'tomorrow', '+1 week', 'next monday', '0', 'not-a-date',
            '2026-10-05 23:30', '26-10-05', '2026-1-5',
        ];

        foreach ($invalidValues as $value) {
            $this->assertNull(parse_date_input($value), json_encode($value).' should not be accepted.');
        }
    }
}
