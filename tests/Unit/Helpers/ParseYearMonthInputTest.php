<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;

class ParseYearMonthInputTest extends TestCase
{
    /** @test */
    public function it_accepts_a_four_digit_year_and_a_two_digit_month(): void
    {
        $this->assertSame([2026, '10'], parse_year_month_input('2026', '10'));
        $this->assertSame([2026, '00'], parse_year_month_input(2026, '00'));
    }

    /** @test */
    public function it_rejects_a_year_that_is_not_exactly_four_digits(): void
    {
        $invalidYears = [
            null, '', 'abc', '366203092', '-1', '12345', '999999999',
            '0', '26', '2O26', ' 2026', '2026 ', 2026.5, ['2026'],
        ];

        foreach ($invalidYears as $year) {
            $this->assertNull(parse_year_month_input($year, '10'), json_encode($year).' should not be accepted as a year.');
        }
    }

    /** @test */
    public function it_rejects_a_month_that_is_not_a_known_two_digit_month(): void
    {
        $invalidMonths = [null, '', 'abc', '13', '99', '1', '10 ', 10, ['10']];

        foreach ($invalidMonths as $month) {
            $this->assertNull(parse_year_month_input('2026', $month), json_encode($month).' should not be accepted as a month.');
        }
    }

    /** @test */
    public function it_does_not_strip_a_leading_zero_from_the_year(): void
    {
        $this->assertSame([202, '01'], parse_year_month_input('0202', '01'));
    }
}
