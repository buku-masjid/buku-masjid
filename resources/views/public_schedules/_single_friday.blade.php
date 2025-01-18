<div class="card fw-bold p-3 mb-2 shadow-lg position-relative">
    <div class="row">
        <div class="col-auto lh-1">
            <span class="badge rounded-pill bg-orange mb-3 me-3">JADWAL JUMAT</span>
            <h3 class="p-0 m-0">{{ $lecturing->day_name }}, {{ $lecturing->full_date }}</h3>
            <span class="date">{{ $lecturing->start_time }} (waktu setempat)</span>
        </div>
        <div class="col px-5 py-3">
            <div class="fs-3">
                <h3>Pembahasan</h3>
                <p class="display-6 fw-thin">"{{ $lecturing->description }}" </p>
            </div>
            <div class="row pt-4 mt-4 border-top">
                <div class="col">
                    <h1 class="fs-3 bm-txt-primary fw-bold p-0 m-0">{{ $lecturing->lecturer_name }}</h1>
                    <span class="fs-4 text-secondary">Khatib</span>
                </div>
                <div class="col">
                    <h1 class="fs-3 bm-txt-primary fw-bold p-0 m-0">{{ $lecturing->imam_name }}</h1>
                    <span class="fs-4 text-secondary">Imam</span>
                </div>
                <div class="col">
                    <h1 class="fs-3 bm-txt-primary fw-bold p-0 m-0">{{ $lecturing->muadzin_name }}</h1>
                    <span class="fs-4 text-secondary">Muadzin</span>
                </div>
            </div>
            
        </div>
    </div>
</div>

<!-- <div class="card">
    <table class="table-sm mb-0">
        <tbody>
            <tr>
                <td class="col-4 col-sm-3">{!! config('lecturing.emoji.date') !!} {{ __('time.day_name') }}/{{ __('time.date') }}</td>
                <td><strong>{{ $lecturing->day_name }}</strong>, {{ $lecturing->full_date }}</td>
            </tr>
            <tr><td>{!! config('lecturing.emoji.time') !!} {{ __('lecturing.time') }}</td><td>{{ $lecturing->start_time }}</td></tr>
            <tr><td>{!! config('lecturing.emoji.lecturer') !!} {{ __('lecturing.friday_lecturer_name') }}</td><td>{{ $lecturing->lecturer_name }}</td></tr>
            @if ($lecturing->imam_name)
                <tr><td>{!! config('lecturing.emoji.imam') !!} {{ __('lecturing.imam_name') }}</td><td>{{ $lecturing->imam_name }}</td></tr>
            @endif
            @if ($lecturing->muadzin_name)
                <tr><td>{!! config('lecturing.emoji.muadzin') !!} {{ __('lecturing.muadzin_name') }}</td><td>{{ $lecturing->muadzin_name }}</td></tr>
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
</div> -->
