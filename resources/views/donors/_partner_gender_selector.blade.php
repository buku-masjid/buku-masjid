<div class="text-center">
    <div class="btn-group">
        {!! link_to_route(
            'donors.search',
            __('app.all'),
            ['gender_code' => null] + request()->all(),
            ['class' => 'btn btn '.(is_null(request('gender_code')) ? 'bg-azure-light' : 'btn-secondary')]
        ) !!}
        @foreach ($genders as $genderCode => $genderName)
            {!! link_to_route(
                'donors.search',
                $genderName,
                ['gender_code' => $genderCode] + request()->all(),
                ['class' => 'btn btn '.(request('gender_code') == $genderCode ? 'bg-azure-light' : 'btn-secondary')]
            ) !!}
        @endforeach
    </div>
</div>
