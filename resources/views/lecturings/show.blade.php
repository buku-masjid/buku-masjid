@extends('layouts.settings')

@section('title', __('lecturing.detail'))

@section('content_settings')
<div class="row justify-content-center mt-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <span class="card-options">{{ $lecturing->audience }}</span>
                {{ __('lecturing.detail') }}
            </div>
            <table class="table table-sm">
                <tbody>
                    <tr>
                        <td class="col-4">{!! config('lecturing.emoji.lecturing') !!} {{ __('lecturing.lecturing') }}</td>
                        <td><strong>{{ $lecturing->day_name }}, {{ $lecturing->time_text }}</strong></td>
                    </tr>
                    <tr><td>{!! config('lecturing.emoji.date') !!} {{ __('time.date') }}</td><td>{{ $lecturing->full_date }}</td></tr>
                    <tr><td>{!! config('lecturing.emoji.time') !!} {{ __('lecturing.time') }}</td><td>{{ $lecturing->time }}</td></tr>
                    <tr><td>{!! config('lecturing.emoji.lecturer') !!} {{ __('lecturing.lecturer_name') }}</td><td>{{ $lecturing->lecturer_name }}</td></tr>
                    @if ($lecturing->imam_name)
                        <tr><td>{!! config('lecturing.emoji.imam') !!} {{ __('lecturing.imam_name') }}</td><td>{{ $lecturing->imam_name }}</td></tr>
                    @endif
                    @if ($lecturing->muadzin_name)
                        <tr><td>{!! config('lecturing.emoji.imam') !!} {{ __('lecturing.muadzin_name') }}</td><td>{{ $lecturing->muadzin_name }}</td></tr>
                    @endif
                    @if ($lecturing->book_title)
                        <tr><td>{!! config('lecturing.emoji.book') !!} {{ __('lecturing.book') }}</td><td>{{ $lecturing->book_title }}</td></tr>
                    @endif
                    <tr><td>{!! config('lecturing.emoji.written_by') !!} {{ __('lecturing.written_by') }}</td><td>{{ $lecturing->book_writer }}</td></tr>
                    <tr><td>{!! config('lecturing.emoji.book_link') !!} {{ __('lecturing.book_link') }}</td><td>{{ $lecturing->book_link }}</td></tr>
                    <tr><td>{!! config('lecturing.emoji.video_link') !!} {{ __('lecturing.video_link') }}</td><td>{{ $lecturing->video_link }}</td></tr>
                    <tr><td>{!! config('lecturing.emoji.audio_link') !!} {{ __('lecturing.audio_link') }}</td><td>{{ $lecturing->audio_link }}</td></tr>
                    <tr><td>{!! config('lecturing.emoji.title') !!} {{ __('lecturing.title') }}</td><td>{{ $lecturing->title }}</td></tr>
                    <tr><td>{!! config('lecturing.emoji.description') !!} {{ __('lecturing.description') }}</td><td>{{ $lecturing->description }}</td></tr>
                </tbody>
            </table>
            <div class="card-footer">
                @can('update', $lecturing)
                    {{ link_to_route('lecturings.edit', __('lecturing.edit'), [$lecturing], ['class' => 'btn btn-warning', 'id' => 'edit-lecturing-'.$lecturing->id]) }}
                @endcan
                @can('create', new App\Models\Lecturing)
                    {{ link_to_route('lecturings.create', __('lecturing.duplicate'), ['original_lecturing_id' => $lecturing->id], ['class' => 'btn btn-success', 'id' => 'duplicate_lecturing-'.$lecturing->id]) }}
                @endcan
                {{ link_to_route('lecturings.index', __('lecturing.back_to_index'), [], ['class' => 'btn btn-link']) }}
            </div>
        </div>
    </div>
</div>
@endsection
