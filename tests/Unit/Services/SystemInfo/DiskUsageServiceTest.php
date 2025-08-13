<?php

namespace Tests\Unit\Services\SystemInfo;

use App\Services\SystemInfo\DiskUsageService;
use Tests\TestCase;

class DiskUsageServiceTest extends TestCase
{
    public function test_percent_used_calculation()
    {
        $service = new class extends DiskUsageService
        {
            public function __construct()
            {
                // Override default constructor logic
                $this->usedBytes = 500 * 1024 * 1024;  // 500 MB
                $this->quotaBytes = 1000 * 1024 * 1024; // 1000 MB
                $this->minRequiredBytes = 5 * 1024 * 1024; // 5 MB
            }
        };

        $this->assertEquals(50.0, $service->getPercentUsed());
    }

    public function test_is_full_returns_true_when_below_threshold()
    {
        $service = new class extends DiskUsageService
        {
            public function __construct()
            {
                $this->usedBytes = 996 * 1024 * 1024;  // 996 MB used
                $this->quotaBytes = 1000 * 1024 * 1024; // 1000 MB total
                $this->minRequiredBytes = 5 * 1024 * 1024; // 5 MB required
            }
        };

        $this->assertTrue($service->getIsFull());
    }

    public function test_is_full_returns_false_when_enough_space()
    {
        $service = new class extends DiskUsageService
        {
            public function __construct()
            {
                $this->usedBytes = 900 * 1024 * 1024;  // 900 MB used
                $this->quotaBytes = 1000 * 1024 * 1024; // 1000 MB total
                $this->minRequiredBytes = 5 * 1024 * 1024; // 5 MB required
            }
        };

        $this->assertFalse($service->getIsFull());
    }

    public function test_remaining_bytes_never_negative()
    {
        $service = new class extends DiskUsageService
        {
            public function __construct()
            {
                $this->usedBytes = 1200 * 1024 * 1024; // more than quota
                $this->quotaBytes = 1000 * 1024 * 1024;
                $this->minRequiredBytes = 5 * 1024 * 1024;
            }
        };

        $this->assertEquals(0, $service->getRemainingBytes());
    }
}
