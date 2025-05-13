<?php

namespace App\Http\Livewire\SystemInfo;

use App\Services\SystemInfo\DiskUsageService;
use Livewire\Component;

class DiskUsage extends Component
{
    public $diskUsage;
    public $diskQuota;
    public $diskUsageInPercent;
    public $percentColor;
    public $isLoading = true;

    public function render()
    {
        return view('livewire.system_info.disk_usage');
    }

    protected DiskUsageService $diskService;

    public function getDiskUsage(): void
    {
        $this->loadUsage();
    }

    private function loadUsage(): void
    {
        $this->diskService = app(DiskUsageService::class);

        $this->diskUsage = $this->diskService->getUsedHuman();
        $this->diskQuota = $this->diskService->getQuotaHuman();
        $this->diskUsageInPercent = $this->diskService->getPercentUsed();
        $this->percentColor = $this->getPercentColor($this->diskService->getPercentUsed());
        $this->isLoading = false;
    }

    private function getPercentColor(float $progressPercent): string
    {
        if ($progressPercent > 75) {
            return 'danger';
        }
        if ($progressPercent > 50) {
            return 'warning';
        }
        if ($progressPercent > 25) {
            return 'info';
        }

        return 'success';
    }
}
