<!-- SCHEDULE ITEM -->
<div class="col-12 col-md-12 col-lg-6 col-xl-4">
    <div class="text-secondary fs-5 row">
        <div class="col-auto"><i class="ti">&#xea52;</i> {{ $lecturing->day_name }}, {{ $lecturing->full_date }} </div>
        <div class="col-auto"><i class="ti">&#xf319;</i> {{ $lecturing->time }}</div>
    </div>
    <div class="single-timeline-content d-flex wow fadeInLeft" data-wow-delay="0.3s" style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInLeft;">
        <div>
            <div class="timeline-icon">
                <img src="{{ asset('images/temp_foto.png') }}">
            </div>
        </div>
        <div class="timeline-text">
            <h5 class="text-secondary">{{ $lecturing->time_text }}</h5>
            <p>
                @if ($lecturing->title)
                    {{ $lecturing->title }}
                @endif
            </p>
            <div class="lh-1 pt-3">
                <h6 class="text-secondary m-0">PENCERAMAH</h6>
                <p class="bm-txt-primary fw-bold">{{ $lecturing->lecturer_name }}</p>
            </div>
        </div>
    </div>
</div>

<!-- <div class="card">
    <table class="table-sm mb-0">
        <tbody>
            <tr>
                <td class="col-4 col-sm-3">{!! config('lecturing.emoji.lecturing') !!} {{ __('lecturing.lecturing') }}</td>
                <td><strong>{{ $lecturing->day_name }}, {{ $lecturing->time_text }}</strong></td>
            </tr>
            <tr><td>{!! config('lecturing.emoji.date') !!} {{ __('time.date') }}</td><td>{{ $lecturing->full_date }}</td></tr>
            <tr><td>{!! config('lecturing.emoji.time') !!} {{ __('lecturing.time') }}</td><td>{{ $lecturing->time }}</td></tr>
            <tr><td>{!! config('lecturing.emoji.lecturer') !!} {{ __('lecturing.lecturer_name') }}</td><td>{{ $lecturing->lecturer_name }}</td></tr>
            @if ($lecturing->book_title)
                <tr><td>&#128216; {{ __('lecturing.book') }}</td><td>{{ $lecturing->book_title }}</td></tr>
            @endif
            @if ($lecturing->book_writer)
                <tr><td>&#9997;&#65039; {{ __('lecturing.written_by') }}</td><td>{{ $lecturing->book_writer }}</td></tr>
            @endif
            @if ($lecturing->book_link)
                <tr><td>&#11015;&#65039; {{ __('lecturing.book_link') }}</td><td>{{ $lecturing->book_link }}</td></tr>
            @endif
            @if ($lecturing->video_link)
                <tr><td>{!! config('lecturing.emoji.video_link') !!} {{ __('lecturing.video_link') }}</td><td>{{ $lecturing->video_link }}</td></tr>
            @endif
            @if ($lecturing->audio_link)
                <tr><td>{!! config('lecturing.emoji.audio_link') !!} {{ __('lecturing.audio_link') }}</td><td>{{ $lecturing->audio_link }}</td></tr>
            @endif
            @if ($lecturing->title)
                <tr><td>{!! config('lecturing.emoji.title') !!} {{ __('lecturing.title') }}</td><td>{{ $lecturing->title }}</td></tr>
            @endif
            @if ($lecturing->description)
                <tr><td>{!! config('lecturing.emoji.description') !!} {{ __('lecturing.description') }}</td><td>{{ $lecturing->description }}</td></tr>
            @endif
        </tbody>
    </table>
</div>
-->