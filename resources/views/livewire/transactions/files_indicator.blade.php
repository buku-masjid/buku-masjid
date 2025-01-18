<span style="font-size: 90%;">
@if ($files->count())
    @if ($files->count() == 1)
        <a href="{{ asset('storage/'.$files->first()->file_path) }}" class="badge badge-light text-dark">
            1 <i class="fe fe-image"></i>
        </a>
    @else
        <div class="dropdown">
            <a class="badge badge-light text-dark" data-toggle="dropdown" aria-expanded="false" style="cursor:pointer">
                {{ $files->count() }} <i class="fe fe-image"></i>
            </a>
            <div class="dropdown-menu">
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
            </div>
        </div>
    @endif
@endif
</span>
