<?php

namespace App\Http\Livewire\SystemInfo;

use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class DiskUsage extends Component
{
    public $diskUsage;
    public $diskQuota;
    public $diskUsageInPercent;
    public $percentColor;
    private $diskUsageInBytes;
    public $isLoading = true;

    public function render()
    {
        return view('livewire.system_info.disk_usage');
    }

    public function getDiskUsage()
    {
        $this->diskUsage = $this->calculateDiskUsage();
        if (config('filesystems.disk_quota')) {
            $this->diskQuota = config('filesystems.disk_quota');
            $this->diskUsageInPercent = get_percent($this->diskUsageInBytes, convert_to_bytes($this->diskQuota));
        }
        $this->percentColor = $this->getPercentColor((float) $this->diskUsageInPercent);
        $this->isLoading = false;
    }

    private function calculateDiskUsage()
    {
        $this->diskUsageInBytes = calculate_folder_size(Storage::path('/'));

        return format_size_units($this->diskUsageInBytes);
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
