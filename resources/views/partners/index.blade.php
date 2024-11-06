@extends('layouts.settings')

@section('title', __('partner.list_by_type', ['type' => $selectedTypeName]))

@section('content_settings')

@include('partners._partner_type_selector')
<hr>
<div class="row">
    <div class="col-md-4">
        <h1 class="page-title">
            {{ __('partner.list_by_type', ['type' => $selectedTypeName]) }}
        </h1>
        <div class="page-subtitle ml-0">
            Berikut adalah {{ strtolower(__('partner.list_by_type', ['type' => $selectedTypeName])) }} {{ Setting::get('masjid_name') }}.
            {{-- {{ __('app.total') }} : {{ $partners->total() }} {{ __('partner.partner_type', ['type' => $selectedTypeName]) }} --}}
        </div>
    </div>
    <div class="col-md-4 text-center">
        @include('partners._partner_gender_selector')
    </div>
    <div class="col-md-4 text-right">
        @can('create', new App\Models\Partner)
            {{ link_to_route('partners.index', __('partner.create', ['type' => $selectedTypeName]), ['action' => 'create'] + request()->only('type_code'), ['class' => 'btn btn-success']) }}
        @endcan
    </div>
</div>

<div class="row justify-content-center">
    {{ Form::open(['method' => 'get', 'class' => 'form-inline mt-3']) }}
    {{ Form::text('search_query', request('search_query'), ['placeholder' => __('partner.search_text'), 'class' => 'date-select form-control mr-1']) }}
    {{ Form::select('is_active', [__('app.inactive'), __('app.active')], request('is_active'), ['placeholder' => __('app.status'), 'class' => 'form-control mr-1']) }}
    <div class="form-group mt-4 mt-sm-0">
        {{ Form::hidden('type_code', request('type_code')) }}
        {{ Form::hidden('gender_code', request('gender_code')) }}
        {{ Form::submit(__('app.search'), ['class' => 'btn btn-info mr-1']) }}
        {{ link_to_route('partners.index', __('app.reset'), request()->only('type_code'), ['class' => 'btn btn-secondary mr-1']) }}
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
                        <th class="text-center">{{ __('partner.level') }}</th>
                        <th class="text-center">{{ __('app.status') }}</th>
                        <th class="text-center">{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($partners as $key => $partner)
                    <tr>
                        <td class="text-center">{{ $partners->firstItem() + $key }}</td>
                        <td>
                            @can('view', $partner)
                                {{ link_to_route('partners.show', $partner->name, [$partner->id]) }}
                            @else
                                {{ $partner->name }}
                            @endcan
                        </td>
                        <td>{{ $partner->phone ? link_to('tel:'.$partner->phone, $partner->phone) : '' }}</td>
                        <td class="text-nowrap text-center">{{ $partner->level }}</td>
                        <td class="text-nowrap text-center">{{ $partner->status }}</td>
                        <td class="text-center text-nowrap">
                            @can('update', $partner)
                                {{ link_to_route(
                                    'partners.index',
                                    __('app.edit'),
                                    ['action' => 'edit', 'id' => $partner->id, 'type_code' => $partner->type_code],
                                    [
                                        'id' => 'edit-partner-'.$partner->id,
                                        'class' => 'btn btn-sm text-dark btn-warning',
                                    ]
                                ) }}
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6">{{ __('app.not_available', ['item' => $selectedTypeName]) }}</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="card-body">{{ $partners->links() }}</div>
        </div>
    </div>
    <div class="col-md-4">
        @if(Request::has('action'))
            @include('partners.forms')
        @endif
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
