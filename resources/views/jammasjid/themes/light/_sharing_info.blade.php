@php
    $quotes = config('jam-masjid.sharing_info');
@endphp

<div class="swiper myVerticalSwiper jm-left-bottom jm-card overflow-hidden" style="height: 180px;">
    <div class="swiper-wrapper">
        @foreach($quotes as $quote)
            <div class="swiper-slide">
                <div class="p-8 text-center" style="font-size:1.5vw; padding: 30px">
                    "{{ $quote['quote'] }}"<br><br>
                    <span style="font-size:1.2rem;font-weight:600;">{{ $quote['source'] }}</span>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var swiper = new Swiper('.myVerticalSwiper', {
            direction: 'vertical',
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            // Optional: fade effect for vertical (Swiper 11+)
            effect: 'slide',
        });
    });
</script>