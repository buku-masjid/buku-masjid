<style>
    .pray-off {
        filter: grayscale(100%);
    }
</style>
<div class="py-4 py-sm-0">
    <div class="d-flex align-items-end gap-2 align-items-end overflow-scroll">
        <div id="Imsak" class="pray-off bg-secondary praytime-item d-flex align-items-end col" style="height: 240px; background-image: url('{{ Storage::url(Setting::get('masjid_photo_path'))}}'); background-repeat: no-repeat; background-position: 0 -30px">
            <div class="prayinfo">
                <h4 class="m-0 d-flex">Imsak</h4>
                <h1 class="m-0 d-block" id="waktu-0">--.--</h1>
            </div>
        </div>
        <div id="Subuh" class="pray-off bg-secondary praytime-item d-flex align-items-end col" style="height: 200px; background-image: url('{{ Storage::url(Setting::get('masjid_photo_path'))}}'); background-repeat: no-repeat;background-position: -95px -70px">
            <div class="prayinfo">
                <h4 class="m-0 d-flex">Subuh</h4>
                <h1 class="m-0 d-block" id="waktu-1">--.--</h1>
            </div>
        </div>
        <div id="Dzuhur" class="pray-off bg-secondary praytime-item d-flex align-items-end col" style="height: 210px; background-image: url('{{ Storage::url(Setting::get('masjid_photo_path'))}}'); background-repeat: no-repeat;background-position: -190px -60px">
            <div class="prayinfo">
                <h4 class="m-0 d-flex">Dzuhur</h4>
                <h1 class="m-0 d-block" id="waktu-2">--.--</h1>
            </div>
        </div>
        <div id="Ashar" class=" pray-off bg-secondary praytime-item d-flex align-items-end col" style="height: 270px; background-image: url('{{ Storage::url(Setting::get('masjid_photo_path'))}}'); background-repeat: no-repeat;background-position: -285px 0px">
            <div class="prayinfo">
                <h4 class="m-0 d-flex">Ashar</h4>
                <h1 class="m-0 d-block" id="waktu-3">--.--</h1>
            </div>
        </div>
        <div id="Maghrib" class="pray-off bg-secondary praytime-item d-flex align-items-end col" style="height: 200px; background-image: url('{{ Storage::url(Setting::get('masjid_photo_path'))}}'); background-repeat: no-repeat;background-position: -380px -70px">
            <div class="prayinfo">
                <h4 class="m-0 d-flex">Maghrib</h4>
                <h1 class="m-0 d-block" id="waktu-4">--.--</h1>
            </div>
        </div>
        <div id="Isya" class="pray-off bg-secondary praytime-item d-flex align-items-end col" style="height: 230px; background-image: url('{{ Storage::url(Setting::get('masjid_photo_path'))}}'); background-repeat: no-repeat;background-position: -475px -40px">
            <div class="prayinfo">
                <h4 class="m-0 d-flex">Isya</h4>
                <h1 class="m-0 d-block" id="waktu-5">--.--</h1>
            </div>
        </div>
    </div>
    <div class="text-end fs-6 text-secondary pt-3">
        <div class="row">
            <div class="col text-start">
                <div id="timeRemaining" class="fs-2 fw-bold"></span></div>
                <span id="timeId"></span>
            </div>
            <div class="col text-end">
                Sumber: myquran.com<br>
                Kota : {{ Setting::get('masjid_city_name') }} (berdasarkan lokasi masjid)<br>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const cityName = "{{ Setting::get('masjid_city_name') }}";
    const cacheKey = `prayer_times_${cityName}`; // Unique key
    labelSholat = ['Imsak', 'Subuh', 'Dzuhur', 'Ashar', 'Maghrib', 'Isya'];
    jadwalSholat = [];

    // Check if data is in localStorage
    const cachedData = localStorage.getItem(cacheKey);

    if (cachedData) {
        const data = JSON.parse(cachedData).data.jadwal;
        jadwalSholat = [data.imsak, data.subuh, data.dzuhur, data.ashar, data.maghrib, data.isya];
        jadwalSholat.forEach((waktu, index) => {
            const element = document.getElementById(`waktu-${index}`);
            if (element) {
                element.textContent = waktu;
            }
        });
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
                    jadwalSholat.forEach((waktu, index) => {
                        const element = document.getElementById(`waktu-${index}`);
                        if (element) {
                            element.textContent = waktu;
                        }
                    });
                }
            }
        });
    }

    function getSisaJadwalSholat(waktuSholatSaatIni) {
        const indexSaatIni = labelSholat.indexOf(waktuSholatSaatIni);

        if (indexSaatIni === -1) {
            return "Waktu sholat tidak valid.";
        }

        const sisaJadwal = labelSholat.slice(indexSaatIni + 1);

        if (sisaJadwal.length === 0) {
            return "Tidak ada waktu sholat tersisa.";
        }
        return sisaJadwal;
    }

    function removeClassFromElements(selector, className) {
        const elements = document.querySelectorAll(selector);

        elements.forEach(element => {
            element.classList.remove(className);
        });
    }

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

        const element = document.getElementById('timeRemaining');
        element.innerHTML = hoursLeft +" <span class='fs-5'>Jam</span> - "+ minutesLeft +" <span class='fs-5'>Menit</span>";
        document.getElementById('timeId').textContent =  "menuju waktu  " + labelSholat[nextIndex];

        const waktuSholatSaatIni = labelSholat[nextIndex - 1];
        const sisaWaktu = getSisaJadwalSholat(waktuSholatSaatIni);

        sisaWaktu.forEach((element, index) => {
            removeClassFromElements(`#${element}`, `pray-off`);
            
        });
    }
    jadwalRemaining();
    setInterval(jadwalRemaining, 60000);
    
</script>
@endpush
