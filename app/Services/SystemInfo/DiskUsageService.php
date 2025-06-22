<?php

namespace App\Services\SystemInfo;

use Illuminate\Support\Facades\Storage;

class DiskUsageService
{
    protected int $usedBytes;
    protected int $quotaBytes;
    protected int $minRequiredBytes;

    public function __construct(int $minRequiredMB = 5)
    {
        $this->usedBytes = $this->calculateUsedBytes();
        $this->quotaBytes = $this->convertToBytes(config('filesystems.disk_quota'));
        $this->minRequiredBytes = $minRequiredMB * 1024 * 1024;
    }

    protected function calculateUsedBytes(): int
    {
        return calculate_folder_size(Storage::path('/'));
    }

    protected function convertToBytes(?string $value): int
    {
        if (is_null($value)) {
            return 0;
        }

        return convert_to_bytes($value);
    }

    public function getUsedHuman(): string
    {
        return format_size_units($this->usedBytes);
    }

    public function getQuotaHuman(): string
    {
        return config('filesystems.disk_quota');
    }

    public function getRemainingBytes(): int
    {
        return max($this->quotaBytes - $this->usedBytes, 0);
    }

    public function getRemainingHuman(): string
    {
        return format_size_units($this->getRemainingBytes());
    }

    public function getPercentUsed(): int|string
    {
        return get_percent($this->usedBytes, $this->quotaBytes);
    }

    public function getIsFull(): bool
    {
        return $this->getRemainingBytes() <= $this->minRequiredBytes;
    }
}
