<?php

namespace App\Services\ShalatTimes;

interface ShalatTimeService
{
    public function getSchedule(string $date);
}
