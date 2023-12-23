<div class="card">
    <table class="table-sm mb-0">
        <tbody>
            <tr>
                <td class="col-4">{!! config('lecturing.emoji.lecturing') !!} {{ __('lecturing.lecturing') }}</td>
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
        <tfoot class="table">
            <tr>
                <td colspan="2" class="text-center">
                    @can('view', $lecturing)
                        {{ link_to_route(
                            'lecturings.show',
                            __('app.show'),
                            [$lecturing],
                            ['id' => 'show-lecturing-' . $lecturing->id]
                        ) }}
                    @endcan
                </td>
            </tr>
        </tfoot>
    </table>
</div>
