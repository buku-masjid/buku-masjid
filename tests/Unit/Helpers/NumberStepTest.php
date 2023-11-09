<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;

class NumberStepTest extends TestCase
{
    /** @test */
    public function it_return_correct_step_based_on_decimal_precision()
    {
        config(['money.precision' => 0]);
        $this->assertEquals('1', number_step());

        config(['money.precision' => 1]);
        $this->assertEquals('0.1', number_step());

        config(['money.precision' => 2]);
        $this->assertEquals('0.01', number_step());

        config(['money.precision' => 3]);
        $this->assertEquals('0.001', number_step());
    }
}
