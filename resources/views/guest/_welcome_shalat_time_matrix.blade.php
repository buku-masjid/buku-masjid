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
<div id="matrix-display2" style="width: 100%; text-align: center" class="pt-2 pt-md-5">
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
    <div class="text-black-50 fs-6 pt-2">Waktu bagian {{ Setting::get('masjid_city_name') }} dan sekitarnya</div>
</div>

@push('scripts')
<script src="{{ asset('js/plugins/matrix-display.js') }}"></script>
<script type="text/javascript">
    const cityName = "{{ Setting::get('masjid_city_name') }}";
    const cacheKey = `prayer_times_${cityName}`; // Unique key
    labelSholat = ['Imsak', 'Subuh', 'Dzuhur', 'Ashar', 'Maghrib', 'Isya'];
    jadwalSholat = [];

    // Check if data is in localStorage
    const cachedData = localStorage.getItem(cacheKey);

    if (cachedData) {
        const data = JSON.parse(cachedData).data.jadwal;
        jadwalSholat = [data.imsak, data.subuh, data.dzuhur, data.ashar, data.maghrib, data.isya];
    } else {
        fetch(`/prayer-times/${cityName}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error("Error:", data.error);
            } else {
                if (data.data) {
                    localStorage.setItem(cacheKey, JSON.stringify(data)); // Store in localStorage
                    jadwalSholat = [data.data.jadwal.imsak, data.data.jadwal.subuh, data.data.jadwal.dzuhur, data.data.jadwal.ashar, data.data.jadwal.maghrib, data.data.jadwal.isya];
                }
            }
        });
    }

    jQuery(document).ready(function() {
        function jadwalRemaining(){
            const now = new Date();
            const currentMinutes = now.getHours() * 60 + now.getMinutes();

            // Jadwal sholat berikutnya
            let nextIndex = jadwalSholat.findIndex(time => {
                const [hour, minute] = time.split(":").map(Number);
                return hour * 60 + minute > currentMinutes;
            });

            if (nextIndex === -1) {
                nextIndex = 0;
            }

            const [nextHour, nextMinute] = jadwalSholat[nextIndex].split(":").map(Number);
            const nextMinutes = nextHour * 60 + nextMinute;

            // hitung sisa waktu
            let remainingMinutes = nextMinutes - currentMinutes;
            if (remainingMinutes < 0) {
                remainingMinutes += 24 * 60;
            }

            const hoursLeft = Math.floor(remainingMinutes / 60);
            const minutesLeft = remainingMinutes % 60;

            return  hoursLeft +" Jam - "+ minutesLeft +" Menit  menuju  waktu  " + labelSholat[nextIndex];
        }

        let dm2;

        function updateMatrixDisplay() {
            // Remove previous MatrixDisplay if exists
            if (dm2) dm2.stop();

            // Initialize new MatrixDisplay with updated text
            dm2 = new MatrixDisplay({
                repeat: true,
                containerEl: '#matrix-display2 .matrix-svg',
                compositions: [
                    {
                        text: jadwalRemaining(),
                        fx: 'left',
                        colors: ['#20716b', '#169a90'],
                        background: '#EEE',
                        invert: false,
                        speed: 60
                    }
                ]
            });

            dm2.run();
        }
        updateMatrixDisplay();

        // Auto-update the display text every minute
        setInterval(updateMatrixDisplay, 60000);
    });
</script>
@endpush
