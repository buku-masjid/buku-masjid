<div wire:init="getDailyAveragesSummary">
    @if ($isLoading)
        <div class="loading-state text-center">
            <img src="{{ asset('images/spinner.gif') }}" alt="Data loading spinner">
        </div>
    @else
        <table class="table table-sm mb-0">
            <tbody>
                @foreach ($dailyAveragesSummary as $dailySummary)
                    <tr>
                        <td>{{ $dailySummary->description }}</td>
                        <td class="text-right" style="color: {{ config('masjid.'.$dailySummary->type_code.'_color') }}">
                            {{ format_number($dailySummary->average) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
