@extends('layouts.app')

@section('title', __('partner.partner_type_donor'))

@section('content')
<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        {!! link_to_route('donors.index', __('dashboard.dashboard'), [], ['class' => 'nav-link'.(in_array(Request::segment(2), ['dashboard', null]) ? ' active' : '')]) !!}
    </li>
    <li class="nav-item">
        {!! link_to_route('donors.search', __('donor.search'), [], ['class' => 'nav-link'.(Request::segment(2) == 'search' ? ' active' : '')]) !!}
    </li>
</ul>

<div class="row mt-4 mt-sm-0">
    <div class="col-md-4 text-center text-sm-left">
        <h1 class="page-title">{{ __('partner.partner_type_donor') }}</h1>
        <div class="page-subtitle ml-0">
            {{ __('donor.donor') }} {{ Setting::get('masjid_name') }}.
        </div>
    </div>
    <div class="col-md-4 mt-3 text-center">
        @include('donors._partner_gender_selector')
    </div>
    <div class="col-md-4 mt-3 text-center text-sm-right">
        @can('create', new App\Models\Partner)
            {{ link_to_route('donors.create', __('donor.create'), [], ['class' => 'btn btn-success']) }}
        @endcan
    </div>
</div>
<div class="row justify-content-center">
    {{ Form::open(['method' => 'get', 'class' => 'form-inline mt-3 mt-sm-0 mx-3']) }}
    {{ Form::text('search_query', request('search_query'), ['placeholder' => __('partner.search_text'), 'class' => 'date-select form-control mr-1']) }}
    {{ Form::select('level_code', $partnerLevels, request('level_code'), ['placeholder' => __('partner.all_level'), 'class' => 'form-control mr-1']) }}
    {{ Form::select('is_active', [__('app.inactive'), __('app.active')], request('is_active'), ['placeholder' => __('app.status'), 'class' => 'form-control mr-1']) }}
    <div class="form-group mt-4 mt-sm-0">
        {{ Form::hidden('type_code', request('type_code')) }}
        {{ Form::hidden('gender_code', request('gender_code')) }}
        {{ Form::submit(__('app.search'), ['class' => 'btn btn-info mr-1']) }}
        {{ link_to_route('donors.search', __('app.reset'), [], ['class' => 'btn btn-secondary mr-1']) }}
    </div>
    {{ Form::close() }}
</div>
<div class="row mt-3">
    <div class="col-md-12">
        <div class="table-responsive-sm">
            <table class="table table-sm table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('app.table_no') }}</th>
                        <th class="text-nowrap">{{ __('partner.name') }}</th>
                        <th class="text-right">{{ __('transaction.transaction') }}</th>
                        <th class="text-nowrap">{{ __('partner.phone') }}</th>
                        <th class="text-center">{{ __('partner.level') }}</th>
                        <th class="text-center">{{ __('app.status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($partners as $key => $partner)
                    <tr>
                        <td class="text-center">{{ $partners->firstItem() + $key }}</td>
                        <td class="text-nowrap">
                            @can('view', $partner)
                                {{ link_to_route(
                                    'donors.show',
                                    $partner->name,
                                    [$partner],
                                    ['id' => 'show-partner-'.$partner->id]
                                ) }}
                            @else
                                {{ $partner->name }}
                            @endcan
                        </td>
                        <td class="text-nowrap text-right">{{ format_number($partner->transactions_sum_amount ?: 0) }}</td>
                        <td class="text-nowrap">
                            {{ $partner->phone ? link_to('https://wa.me/'.str_replace([' ', '+', '(', ')'], '', $partner->phone), $partner->phone) : '' }}
                        </td>
                        <td class="text-nowrap text-center">{{ $partner->level }}</td>
                        <td class="text-nowrap text-center">{{ $partner->status }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6">{{ __('app.not_available', ['item' => __('donor.donor')]) }}</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="card-body">{{ $partners->links() }}</div>
        </div>
    </div>
</div>

@endsection

@prepend('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endprepend

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
