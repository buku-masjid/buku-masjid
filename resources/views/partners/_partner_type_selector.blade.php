@if (count($partnerTypes) > 1)
    <div class="text-center my-4">
        <div class="btn-group">
            @foreach ($partnerTypes as $partnerTypeCode => $partnerTypeName)
                {!! link_to_route(
                    'partners.index',
                    $partnerTypeName,
                    ['type_code' => $partnerTypeCode] + request()->all(),
                    ['class' => 'btn btn-pill '.($selectedTypeCode == $partnerTypeCode ? 'btn-primary' : 'btn-secondary')]
                ) !!}
            @endforeach
        </div>
    </div>
@endif
