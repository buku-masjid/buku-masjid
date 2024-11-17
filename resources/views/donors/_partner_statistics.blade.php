</div>

<div class="bg-white py-6 mb-5 mt-3 mt-sm-5">
    <div class="container">
        <div class="row" style="min-height: 8em;">
            <div class="col-md-4 text-center text-md-left">
                <h1 class="page-title">{{ $selectedTypeName }}</h1>
                <div class="page-subtitle ml-0">
                    Berikut adalah data {{ __('transaction.in_out') }} {{ $selectedTypeName }} {{ Setting::get('masjid_name') }}.
                </div>
            </div>
            <div class="col-md-4 mt-3 mt-sm-0 text-center">
            </div>
            <div class="col-md-4 mt-3 mt-sm-0 text-center text-md-right">
                @livewire('partners.total-income-from-partner', ['partnerTypeCode' => $selectedTypeCode])
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                @livewire('partners.books-count')
            </div>
            <div class="col-md-4">
                @livewire('partners.level-stats', ['partnerTypeCode' => $selectedTypeCode])
            </div>
            <div class="col-md-4">
                @livewire('partners.gender-stats', ['partnerTypeCode' => $selectedTypeCode])
            </div>
        </div>
        @livewire('partners.income-from-partner-graph', ['partnerTypeCode' => $selectedTypeCode])
    </div>
</div>
<div class="container">
