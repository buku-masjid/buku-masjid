@if (request('action') == 'create')
    @can('create', new App\Models\Partner)
    <div id="partnerModal" class="modal" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('partner.create', ['type' => $selectedTypeName]) }}</h5>
                    {{ link_to_route('partners.index', '', ['type_code' => $selectedTypeCode], ['class' => 'close']) }}
                </div>
                {!! Form::open(['route' => 'partners.store']) !!}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            {!! FormField::text('name', ['required' => true, 'label' => __('partner.name')]) !!}
                            <div class="row">
                                <div class="col-md-5">
                                    {!! FormField::select('gender_code', $genders, [
                                        'value' => old('gender_code', request('gender_code')),
                                        'placeholder' => false,
                                        'label' => __('app.gender'),
                                    ]) !!}
                                    {{ Form::hidden('type_code', $selectedTypeCode) }}
                                </div>
                                <div class="col-md-7">
                                    {!! FormField::text('phone', ['label' => __('partner.phone'), 'type' => 'number']) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">{!! FormField::text('pob', ['label' => __('partner.pob')]) !!}</div>
                                <div class="col-md-4">{!! FormField::text('dob', ['label' => __('partner.dob')]) !!}</div>
                            </div>
                            {!! FormField::textarea('address', ['label' => __('address.address')]) !!}
                            <div class="row">
                                <div class="col-6">{!! FormField::text('rt', ['label' => __('address.rt')]) !!}</div>
                                <div class="col-6">{!! FormField::text('rw', ['label' => __('address.rw')]) !!}</div>
                            </div>
                            {!! FormField::textarea('description', ['label' => __('partner.description')]) !!}
                        </div>
                        <div class="col-md-6">
                            @if ($partnerLevels)
                                {!! FormField::select('level_code', $partnerLevels, [
                                    'value' => old('level_code', request('level_code')),
                                    'placeholder' => false,
                                    'label' => __('partner.level'),
                                ]) !!}
                            @else
                                {{ Form::hidden('level_code') }}
                            @endif
                            {!! FormField::select('religion_id', __('partner.religions'), ['label' => __('partner.religion'), 'placeholder' => __('app.unknown')]) !!}
                            {!! FormField::select('work_id', $availableWorks, ['label' => __('partner.work'), 'placeholder' => __('app.unknown')]) !!}
                            {!! FormField::text('work', ['label' => __('partner.work_detail')]) !!}
                            {!! FormField::select('marital_status_id', __('partner.marital_statuses'), ['label' => __('partner.marital_status'), 'placeholder' => __('app.unknown')]) !!}
                            {!! FormField::select('financial_status_id', __('partner.financial_statuses'), ['label' => __('partner.financial_status'), 'placeholder' => __('app.unknown')]) !!}
                            {!! FormField::select('activity_status_id', __('partner.activity_statuses'), ['label' => __('partner.activity_status'), 'placeholder' => __('app.unknown')]) !!}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    {!! Form::submit(__('partner.create', ['type' => $selectedTypeName]), ['class' => 'btn btn-success']) !!}
                    {{ link_to_route('partners.index', __('app.cancel'), ['type_code' => $selectedTypeCode], ['class' => 'btn btn-secondary']) }}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    @endcan
@endif

@if (request('action') == 'edit' && $editablePartner)
    @can('update', $editablePartner)
    <div id="partnerModal" class="modal" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('partner.edit', ['type' => $selectedTypeName]) }}</h5>
                    {{ link_to_route('partners.index', '', ['type_code' => $selectedTypeCode], ['class' => 'close']) }}
                </div>
                {!! Form::model($editablePartner, ['route' => ['partners.update', $editablePartner], 'method' => 'patch']) !!}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            {!! FormField::text('name', ['required' => true, 'label' => __('partner.name')]) !!}
                            <div class="row">
                                <div class="col-md-6">
                                    {!! FormField::select('gender_code', $genders, [
                                        'placeholder' => false,
                                        'label' => __('app.gender'),
                                    ]) !!}
                                    {{ Form::hidden('type_code', $selectedTypeCode) }}
                                </div>
                                <div class="col-md-6">
                                    {!! FormField::text('phone', ['label' => __('partner.phone'), 'type' => 'number']) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">{!! FormField::text('pob', ['label' => __('partner.pob')]) !!}</div>
                                <div class="col-md-4">{!! FormField::text('dob', ['label' => __('partner.dob')]) !!}</div>
                            </div>
                            {!! FormField::textarea('address', ['label' => __('address.address')]) !!}
                            <div class="row">
                                <div class="col-6">{!! FormField::text('rt', ['label' => __('address.rt')]) !!}</div>
                                <div class="col-6">{!! FormField::text('rw', ['label' => __('address.rw')]) !!}</div>
                            </div>
                            {!! FormField::textarea('description', ['label' => __('partner.description')]) !!}
                        </div>
                        <div class="col-md-6">
                            @if ($partnerLevels)
                                {!! FormField::select('level_code', $partnerLevels, [
                                    'value' => old('level_code', request('level_code')),
                                    'placeholder' => false,
                                    'label' => __('partner.level'),
                                ]) !!}
                            @else
                                {{ Form::hidden('level_code') }}
                            @endif
                            {!! FormField::select('religion_id', __('partner.religions'), ['label' => __('partner.religion'), 'placeholder' => __('app.unknown')]) !!}
                            {!! FormField::select('work_id', __('partner.works'), ['label' => __('partner.work'), 'placeholder' => __('app.unknown')]) !!}
                            {!! FormField::text('work', ['label' => __('partner.work_detail')]) !!}
                            {!! FormField::select('marital_status_id', __('partner.marital_statuses'), ['label' => __('partner.marital_status'), 'placeholder' => __('app.unknown')]) !!}
                            {!! FormField::select('financial_status_id', __('partner.financial_statuses'), ['label' => __('partner.financial_status'), 'placeholder' => __('app.unknown')]) !!}
                            {!! FormField::select('activity_status_id', __('partner.activity_statuses'), ['label' => __('partner.activity_status'), 'placeholder' => __('app.unknown')]) !!}
                            {!! FormField::radios('is_active', [__('app.inactive'), __('app.active')], ['label' => __('app.status')]) !!}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    {!! Form::submit(__('partner.update', ['type' => $selectedTypeName]), ['class' => 'btn btn-success']) !!}
                    {{ link_to_route('partners.index', __('app.cancel'), ['type_code' => $selectedTypeCode], ['class' => 'btn btn-secondary']) }}
                    @can('delete', $editablePartner)
                        {!! link_to_route(
                            'partners.index',
                            __('app.delete'),
                            ['action' => 'delete', 'id' => $editablePartner->id, 'type_code' => $selectedTypeCode],
                            ['id' => 'del-partner-'.$editablePartner->id, 'class' => 'btn btn-danger float-left']
                        ) !!}
                    @endcan
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    @endcan
@endif

@if (request('action') == 'delete' && $editablePartner)
    @can('delete', $editablePartner)
    <div id="partnerModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('partner.delete', ['type' => $selectedTypeName]) }}</h5>
                    {{ link_to_route('partners.index', '', ['type_code' => $selectedTypeCode], ['class' => 'close']) }}
                </div>
                <div class="modal-body">
                    <label class="control-label">{{ __('partner.name') }}</label>
                    <p>{{ $editablePartner->name }}</p>
                    <label class="control-label">{{ __('partner.phone') }}</label>
                    <p>{{ $editablePartner->phone }}</p>
                    <label class="control-label">{{ __('partner.work') }}</label>
                    <p>{{ $editablePartner->work }}</p>
                    <label class="control-label">{{ __('partner.address') }}</label>
                    <p>{{ $editablePartner->address }}</p>
                    <label class="control-label">{{ __('partner.description') }}</label>
                    <p>{{ $editablePartner->description }}</p>
                    {!! $errors->first('partner_id', '<span class="form-error small">:message</span> ') !!}
                </div>
                <hr style="margin:0">
                <div class="modal-body">{{ __('app.delete_confirm') }}</div>
                <div class="modal-footer">
                    {!! FormField::delete(
                        ['route' => ['partners.destroy', $editablePartner], 'class' => ''],
                        __('app.delete_confirm_button'),
                        ['class' => 'btn btn-danger'],
                        ['partner_id' => $editablePartner->id]
                    ) !!}
                    {{ link_to_route('partners.index', __('app.cancel'), ['type_code' => $selectedTypeCode], ['class' => 'btn btn-secondary']) }}
                </div>
            </div>
        </div>
    </div>
    @endcan
@endif
