@php
    $quotes = config('public_display.sharing_info');
@endphp

<div class="swiper myVerticalSwiper jm-card overflow-hidden h-[300px] lg:h-1/2">
    <div class="swiper-wrapper">
        @foreach($quotes as $quote)
            <div class="swiper-slide flex items-center overflow-hidden">
                <div class="p-[30px]  lg:p-5 2xl:p-8 text-center text-2xl 2xl:text-3xl align-middle">
                    "{{ $quote['quote'] }}"
                    <span class="text-lg 2xl:text-2xl block pt-5">{{ $quote['source'] }}</span>
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
