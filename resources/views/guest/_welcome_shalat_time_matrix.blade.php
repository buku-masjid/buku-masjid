@php
    $y1 = '0';
    $y2 = '5';
    $y3 = '10';
    $y4 = '15';
    $y5 = '20';
    $y6 = '25';
    $y7 = '30';
    $width = '4';
    $height = '4';
@endphp
<div id="matrix-display" style="width: 100%; text-align: center" class="pt-2 pt-md-5">
    <div class="shadow-sm" style="width: auto; display: inline; border-radius: 10px; padding: 16px 10px; background-color: white; border: 1px solid #eee">
        <svg class="matrix-svg" style="width: 299px; height: 38px">
            <g transform="translate(-5,0)">
                @for ($i = 1; $i <= 60; $i++)
                    <g transform="translate({{ $i * 5 }}, 0)">
                        <rect width="{{ $width }}" height="{{ $height }}" y="{{ $y1 }}" fill="#eeeeee"></rect>
                        <rect width="{{ $width }}" height="{{ $height }}" y="{{ $y2 }}" fill="#eeeeee"></rect>
                        <rect width="{{ $width }}" height="{{ $height }}" y="{{ $y3 }}" fill="#eeeeee"></rect>
                        <rect width="{{ $width }}" height="{{ $height }}" y="{{ $y4 }}" fill="#eeeeee"></rect>
                        <rect width="{{ $width }}" height="{{ $height }}" y="{{ $y5 }}" fill="#eeeeee"></rect>
                        <rect width="{{ $width }}" height="{{ $height }}" y="{{ $y6 }}" fill="#eeeeee"></rect>
                        <rect width="{{ $width }}" height="{{ $height }}" y="{{ $y7 }}" fill="#eeeeee"></rect>
                    </g>
                @endfor
            </g>
        </svg>
    </div>
    <div class="text-black-50 fs-6 pt-2 text-capitalize"><i class="ti">&#xeae8;</i> <span id="region_name"></span></div>
</div>

@push('scripts')
<script src="{{ asset('js/plugins/matrix-display.js') }}"></script>
<script type="text/javascript">
jQuery(document).ready(function() {
    let matrixDisplay;

    function updateMatrixDisplay(shalatTimeData) {
        // Remove previous MatrixDisplay if exists
        if (matrixDisplay) matrixDisplay.stop();

        // Initialize new MatrixDisplay with updated text
        matrixDisplay = new MatrixDisplay({
            repeat: true,
            containerEl: '#matrix-display',
            compositions: [
                {
                    text: shalatTimeRemaining(shalatTimeData),
                    fx: 'left',
                    colors: ['#20716b', '#169a90'],
                    background: '#EEE',
                    invert: false,
                    speed: 60
                }
            ]
        });

        matrixDisplay.run();
    }

    const cacheKey = `shalat_times_{{ now()->format('Ymd') }}`;
    const cachedData = localStorage.getItem(cacheKey);
    const shalatDailySchedule = {!! json_encode(__('shalat_time.daily_schedules')) !!};

    if (cachedData) {
        const shalatTimeData = JSON.parse(cachedData);
        document.getElementById('region_name').textContent = shalatTimeData.location.toLowerCase() + ', ' + shalatTimeData.region.toLowerCase();
        updateMatrixDisplay(shalatTimeData);
        setInterval(updateMatrixDisplay(shalatTimeData), 60000);
    } else {
        fetch("{{ route('api.public_shalat_time.show') }}")
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error("Error:", data.error);
            } else {
                const shalatTimeData = data;
                localStorage.setItem(cacheKey, JSON.stringify(shalatTimeData));
                document.getElementById('region_name').textContent = shalatTimeData.location + ', ' + shalatTimeData.region;
                updateMatrixDisplay(shalatTimeData);
                setInterval(updateMatrixDisplay(shalatTimeData), 60000);
            }
        });
    }

    function shalatTimeRemaining(shalatTimeData) {
        const now = new Date();
        const currentMinutes = now.getHours() * 60 + now.getMinutes();
        nextShalatTime = 'imsak';
        // Ref: https://www.geeksforgeeks.org/how-to-get-a-key-in-a-javascript-object-by-its-value
        for (let prop in shalatTimeData.schedules) {
            const value = shalatTimeData.schedules[prop];
            if (value.match(/^\d{2,}:\d{2}$/)) {
                const [hour, minute] = value.split(":").map(Number);
                if (hour * 60 + minute > currentMinutes) {
                    nextShalatTime = prop;
                    break;
                }
            }
        }

        const [nextHour, nextMinute] = shalatTimeData.schedules[nextShalatTime].split(":").map(Number);
        const nextMinutes = nextHour * 60 + nextMinute;

        // hitung sisa waktu
        let remainingMinutes = nextMinutes - currentMinutes;
        if (remainingMinutes < 0) {
            remainingMinutes += 24 * 60;
        }

        const hoursLeft = Math.floor(remainingMinutes / 60);
        const minutesLeft = remainingMinutes % 60;

        return  hoursLeft +" {{ __('time.hours') }} - "+ minutesLeft +" {{ __('time.minutes') }}  {{ __('shalat_time.time_before_text') }}  " + shalatDailySchedule[nextShalatTime];
    }
});
</script>
@endpush
