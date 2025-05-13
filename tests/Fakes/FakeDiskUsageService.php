<?php

namespace Tests\Fakes;

use App\Services\SystemInfo\DiskUsageService;

class FakeDiskUsageService extends DiskUsageService
{
    public function __construct()
    {
    }

    public function getUsedHuman(): string
    {
        return '1GB';
    }

    public function getQuotaHuman(): string
    {
        return '2GB';
    }

    public function getPercentUsed(): int|string
    {
        return 50.0;
    }

    public function getIsFull(): bool
    {
        return true;
    }
}
