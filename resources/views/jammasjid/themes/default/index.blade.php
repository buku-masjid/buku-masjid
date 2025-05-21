

@extends('jammasjid.themes.default.layout')

@section('content')
    <div class="jm-section jm-header">
        <div class="jm-left-column pe-3 align-items-center justify-content-start jm-card me-4 text-start">
            @include('jammasjid.themes.default._masjid_info')
        </div>
        <div class="jm-right-column jm-card justify-content-center align-items-center overflow-hidden">
            @livewire('jam-masjid.financial-summary')
        </div>
    </div>
    <div class="jm-section jm-main-content">
        <div class="jm-left-column col-6 split py-4 pe-4">
            @include('jammasjid.themes.default._date_time')
            @include('jammasjid.themes.default._sharing_info')
        </div>
        <div class="jm-right-column col-6 py-4">
            @include('jammasjid.themes.default._carousel')
        </div>
    </div>
    <div class="jm-section jm-footer">
        @include('jammasjid.themes.default._shalat_time')
    </div>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment-hijri@2.1.2/build/moment-hijri.min.js"></script>
<script>
    function gmod(n, m){
        return ((n % m) + m) % m;
    }

    /* @param {Date}   date   - optional, default is today
    ** @param {number} adjust - optiona, days to adjust date by
    */
    function kuwaiticalendar(date, adjust) {
        var today = date? new Date(+date) : new Date();
        if (adjust) {
            today.setDate(today.getDate() + +adjust);
        }

        var day = today.getDate();
        var month = today.getMonth();
        var year = today.getFullYear();
        var m = month + 1;
        var y = year;

        if (m < 3) {
            y -= 1;
            m += 12;
        }

        var a = Math.floor(y / 100);
        var b = 2 - a + Math.floor(a / 4);

        if (y < 1583) b = 0;
        if (y == 1582) {
            if (m > 10)  b = -10;
            if (m == 10) {
            b = 0;
            if (day > 4) b = -10;
            }
        }

        var jd = Math.floor(365.25*(y+4716))+Math.floor(30.6001*(m+1))+day+b-1524;

        b = 0;
        if(jd > 2299160){
            a = Math.floor((jd - 1867216.25) / 36524.25);
            b = 1+a-Math.floor(a / 4);
        }

        var bb = jd+b+1524;
        var cc = Math.floor((bb - 122.1) / 365.25);
        var dd = Math.floor(365.25 * cc);
        var ee = Math.floor((bb - dd) / 30.6001);
        day = (bb - dd) - Math.floor(30.6001 * ee);
        month = ee - 1;

        if (ee > 13) {
            cc += 1;
            month = ee - 13;
        }
        year = cc - 4716;
        var wd = gmod(jd + 1, 7) + 1;

        var iyear = 10631./30.;
        var epochastro = 1948084;
        var epochcivil = 1948085;

        var shift1 = 8.01 / 60.;

        var z = jd-epochastro;
        var cyc = Math.floor(z/10631.);
        z = z - 10631 * cyc;
        var j = Math.floor((z - shift1) / iyear);
        var iy = 30 * cyc + j;
        z = z - Math.floor(j * iyear + shift1);
        var im = Math.floor((z + 28.5001) / 29.5);

        if (im == 13) im = 12;
        var id = z-Math.floor(29.5001*im-29);

        return [
            day,       //calculated day (CE)
            month - 1, //calculated month (CE)
            year,      //calculated year (CE)
            jd - 1,    //julian day number
            wd - 1,    //weekday number
            id,        //islamic date
            im - 1,    //islamic month
            iy         //islamic year
        ];
    }

    function writeIslamicDate(date, adjustment) {
    var wdNames = ["Ahad", "Ithnin", "Thulatha", "Arbaa", "Khams", "Jumuah", "Sabt"];
    var iMonthNames = ["Muharram", "Safar", "Rabi'ul Awwal", "Rabi'ul Akhir", "Jumadal Ula", "Jumadal Akhira",
                        "Rajab", "Sha'ban", "Ramadan", "Shawwal", "Dhul Qa'ada", "Dhul Hijja"];
    var iDate = kuwaiticalendar(date, adjustment);
    var outputIslamicDate = iDate[5] + " " +
                            iMonthNames[iDate[6]] + " " + iDate[7] + " AH";
    return outputIslamicDate;
    }

    function updateTimeInfo() {
        // Current time
        const now = moment();
        document.querySelector('.big-time').textContent = now.format('HH:mm');
        // Current Gregorian date
        document.querySelector('.text-date').textContent = now.format('DD MMMM YYYY');
        // Current Islamic date (Hijri)
        //const hijri = moment().format('iYYYY iMMMM iDD');
        let hijriDate = writeIslamicDate();
        // Remove the 'i' prefix and add 'H' for the year
        document.querySelector('.text-islamic-date').textContent = hijriDate;
    }
    updateTimeInfo();
    setInterval(updateTimeInfo, 1000);

    // Calculate the total width of all child elements
    const myDiv = document.getElementById("slide-track");
    const childElements = myDiv.children;
    const numberOfChildren = childElements.length;
    let totalWidth = 0; 
    //console.log("Number of children:", numberOfChildren);

    for (let i = 0; i < numberOfChildren; i++) {
        const child = childElements[i];
        const width = child.offsetWidth;
        //console.log(`Element ${i + 1} width: ${width}px`);
        totalWidth += width;
    }

    myDiv.style.width = totalWidth + 'px'; 
</script>
@endsection

@section('styles')
<style>
    html, body {
        height: 100%; /* Make sure the html and body take up the full viewport height */
        margin: 0;  /* Remove default body margins */
        display: flex; /* Enable flexbox for the body */
        flex-direction: column; /* Stack children vertically */
        margin-bottom:0 !important;
    }
   
</style>
@endsection