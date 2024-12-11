@extends('layouts.app')

@section('title', __('partner.search'))

@section('content')

<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        {!! link_to_route('partners.index', __('dashboard.dashboard'), [], ['class' => 'nav-link'.(in_array(Request::segment(2), ['dashboard', null]) ? ' active' : '')]) !!}
    </li>
    <li class="nav-item">
        {!! link_to_route('partners.search', __('partner.search'), [], ['class' => 'nav-link'.(Request::segment(2) == 'search' ? ' active' : '')]) !!}
    </li>
</ul>

<div class="row mt-4 mt-sm-0">
    <div class="col-md-4 text-center text-sm-left">
        <h1 class="page-title">
            {{ __('partner.list') }}
        </h1>
        <div class="page-subtitle ml-0">
            {{ __('app.total') }} : {{ $partners->total() }} {{ __('partner.partner') }}
            {{ Setting::get('masjid_name') }}.
        </div>
    </div>
    <div class="col-md-4 mt-3 text-center">
    </div>
    <div class="col-md-4 mt-3 text-center text-sm-right">
        @can('create', new App\Models\Partner)
            {{ link_to_route('partners.create', __('partner.create'), [], ['class' => 'btn btn-success']) }}
        @endcan
    </div>
</div>

<div class="row">
    {{ Form::open(['method' => 'get', 'class' => 'form-inline mt-3 mx-3 justify-content-center']) }}
    {{ Form::text('search_query', request('search_query'), ['placeholder' => __('partner.search_text'), 'class' => 'date-select form-control mr-1 mt-2']) }}
    {{ Form::select('gender_code', $genders, request('gender_code'), ['placeholder' => __('app.gender'), 'class' => 'form-control mr-1 mt-2']) }}
    {{ Form::select('type_code', $partnerTypes, request('type_code'), ['placeholder' => __('partner.all_type'), 'class' => 'form-control mr-1 mt-2']) }}
    @if ($partnerLevels)
        {{ Form::select('level_code', $partnerLevels, request('level_code'), ['placeholder' => __('partner.all_level'), 'class' => 'form-control mr-1 mt-2']) }}
    @endif
    {{ Form::select('age_group_code', __('partner.age_groups') + ['null' => __('app.unknown')], request('age_group_code'), ['placeholder' => __('partner.age_group'), 'class' => 'form-control mr-1 mt-2']) }}
    {{ Form::select('work_type_id', __('partner.work_types') + ['null' => __('app.unknown')], request('work_type_id'), ['placeholder' => __('partner.work'), 'class' => 'form-control mr-1 mt-2']) }}
    {{ Form::select('marital_status_id', __('partner.marital_statuses') + ['null' => __('app.unknown')], request('marital_status_id'), ['placeholder' => __('partner.marital_status'), 'class' => 'form-control mr-1 mt-2']) }}
    {{ Form::select('financial_status_id', __('partner.financial_statuses') + ['null' => __('app.unknown')], request('financial_status_id'), ['placeholder' => __('partner.financial_status'), 'class' => 'form-control mr-1 mt-2']) }}
    {{ Form::select('activity_status_id', __('partner.activity_statuses') + ['null' => __('app.unknown')], request('activity_status_id'), ['placeholder' => __('partner.activity_status'), 'class' => 'form-control mr-1 mt-2']) }}
    {{ Form::select('religion_id', __('partner.religions') + ['null' => __('app.unknown')], request('religion_id'), ['placeholder' => __('partner.religion'), 'class' => 'form-control mr-1 mt-2']) }}
    {{ Form::select('is_active', [__('app.inactive'), __('app.active')], request('is_active'), ['placeholder' => __('app.status'), 'class' => 'form-control mr-1 mt-2']) }}
    <div class="form-group mt-4 mt-sm-2">
        {{ Form::submit(__('app.search'), ['class' => 'btn btn-info mr-1']) }}
        {{ link_to_route('partners.search', __('app.reset'), [], ['class' => 'btn btn-secondary mr-1']) }}
    </div>
    {{ Form::close() }}
</div>

<div class="row mt-3">
    <div class="col-md-12">
        <div class=" table-responsive-sm">
            <table class="table table-sm table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('app.table_no') }}</th>
                        <th class="text-nowrap">{{ __('partner.name') }}</th>
                        <th class="text-nowrap">{{ __('partner.phone') }}</th>
                        <th class="text-center">{{ __('partner.type') }}</th>
                        @if ($partnerLevels)
                            <th class="text-center">{{ __('partner.level') }}</th>
                        @endif
                        <th class="text-nowrap d-none d-sm-table-cell">{{ __('partner.work') }}</th>
                        <th class="text-center">{{ __('app.status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($partners as $key => $partner)
                    <tr>
                        <td class="text-center">{{ $partners->firstItem() + $key }}</td>
                        <td>
                            @can('view', $partner)
                                {{ link_to_route(
                                    'partners.show',
                                    $partner->name,
                                    [$partner],
                                    ['id' => 'show-partner-'.$partner->id]
                                ) }}
                            @else
                                {{ $partner->name }}
                            @endcan
                        </td>
                        <td>{{ $partner->phone ? link_to('https://wa.me/'.str_replace([' ', '+', '(', ')'], '', $partner->phone), $partner->phone) : '' }}</td>
                        <td class="text-nowrap text-center">{{ $partner->type }}</td>
                        @if ($partnerLevels)
                            <td class="text-nowrap text-center">{{ $partner->level }}</td>
                        @endif
                        <td class="text-nowrap d-none d-sm-table-cell">{{ $partner->work_type }}</td>
                        <td class="text-nowrap text-center">{{ $partner->status }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6">{{ __('partner.not_found') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="card-body">{{ $partners->links() }}</div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    $('#partnerModal').modal({
        show: true,
        backdrop: 'static',
    });
})();
</script>
@endpush
