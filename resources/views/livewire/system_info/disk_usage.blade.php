<div wire:init="getDiskUsage" class="card">
    <div class="card-body text-center">
        <div class="h5">Disk Usage</div>
        @if ($isLoading)
            <div class="loading-state text-center w-100">
                <img src="{{ asset('images/spinner.gif') }}" alt="Data loading spinner">
            </div>
        @else
            @if ($diskUsageInPercent)
                <div class="h2 font-weight-bold mb-4 text-{{ $percentColor }}">{{ $diskUsageInPercent }} %</div>
                <div class="progress progress-sm">
                    <div class="progress-bar bg-{{ $percentColor }}" style="width: {{ $diskUsageInPercent }}%"></div>
                </div>
                <div class="text-muted">{{ $diskUsage }} of {{ $diskQuota }}</div>
            @else
                <div class="h2 font-weight-bold text-info">{{ $diskUsage }}</div>
            @endif
        @endif
    </div>
</div>
