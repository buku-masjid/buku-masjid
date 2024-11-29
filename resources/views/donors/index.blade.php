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

    @include('donors._partner_statistics')
</div>

<div class="bg-white py-6 mb-5 mt-3 mt-sm-5">
    <div class="container">
        @livewire('donors.income-dashboard', ['book' => $selectedBook, 'year' => $selectedYear, 'month' => $selectedMonth])
    </div>
</div>
<div class="mt-3 mt-sm-5">
    <div class="container">
        @livewire('donors.book-dashboard', ['book' => $selectedBook, 'year' => $selectedYear, 'month' => $selectedMonth])
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
