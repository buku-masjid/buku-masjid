@if (request('action') == 'change_levels' && $partner && $availableLevels)
    @can('update', $partner)
    <div id="partnerModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('partner.change_levels') }}</h5>
                    {{ link_to_route('partners.show', '', [$partner], ['class' => 'close']) }}
                </div>
                {!! Form::model($partner, ['route' => ['partners.change_levels', $partner], 'method' => 'patch']) !!}
                <div class="modal-body">
                    @foreach ($partnerTypes as $typeCode => $typeName)
                        @if (isset($availableLevels[$typeName]))
                            {!! FormField::select('level_code['.$typeCode.']', $availableLevels[$typeName], [
                                'value' => old('level_code.'.$typeCode, request('level_code.'.$typeCode)),
                                'placeholder' => false,
                                'label' => $typeName,
                            ]) !!}
                        @endif
                    @endforeach
                </div>
                <div class="modal-footer">
                    {!! Form::submit(__('app.update'), ['class' => 'btn btn-success']) !!}
                    {{ link_to_route('partners.show', __('app.cancel'), [$partner], ['class' => 'btn btn-secondary']) }}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    @endcan
@endif
