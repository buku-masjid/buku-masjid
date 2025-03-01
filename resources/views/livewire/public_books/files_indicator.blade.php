<span style="font-size: 90%;">
@if ($files->count())
    @if ($files->count() == 1)
        <a href="{{ asset('storage/'.$files->first()->file_path) }}" class="badge bg-light text-bg-light">
            1 <i class="ti ti-photo fs-3"></i>
        </a>
    @else
        <div class="btn-group dropstart">
            <button type="button" class="badge bg-light text-bg-light" data-bs-toggle="dropdown" aria-expanded="false">
                {{ $files->count() }} <i class="ti ti-photo fs-3"></i>
            </button>
            <ul class="dropdown-menu">
                @foreach ($files as $key => $file)
                    <div class="dropdown-item">
                        <a href="{{ asset('storage/'.$file->file_path) }}">
                            @if ($file->title)
                                <div>{{ $file->title }}</div>
                            @else
                                <div>{{ __('transaction.files') }} {{ 1 + $key }}</div>
                            @endif
                        </a>
                    </div>
                @endforeach
            </ul>
        </div>
    @endif
@endif
</span>
