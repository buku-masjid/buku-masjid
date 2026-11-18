<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;

class ReportTitleWithPeriodTest extends TestCase
{
    /** @test */
    public function it_appends_the_period_label_and_the_date_range_to_the_report_title()
    {
        $this->assertEquals(
            'Per Pekan Periode 09 - 15 Nov 2026',
            get_report_title_with_period('Per Pekan', '2026-11-09', '2026-11-15')
        );
    }

    /** @test */
    public function it_keeps_both_years_when_the_range_crosses_a_year_boundary()
    {
        $this->assertEquals(
            'Per Pekan Periode 29 Des 2025 - 04 Jan 2026',
            get_report_title_with_period('Per Pekan', '2025-12-29', '2026-01-04')
        );
    }

    /** @test */
    public function it_keeps_a_custom_report_title_intact_before_the_period()
    {
        $this->assertEquals(
            'Kas Pekanan Periode 01 - 31 Okt 2026',
            get_report_title_with_period('Kas Pekanan', '2026-10-01', '2026-10-31')
        );
    }
}
