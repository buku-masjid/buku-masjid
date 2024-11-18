<div class="row" style="min-height: 8em;">
    <div class="col-md-4 text-center text-md-left">
        <h1 class="page-title">{{ __('partner.partner_type_donor') }}</h1>
        <div class="page-subtitle ml-0">
            Berikut adalah data {{ __('transaction.in_out') }} {{ __('partner.partner_type_donor') }} {{ Setting::get('masjid_name') }}.
        </div>
    </div>
    <div class="col-md-4 mt-3 mt-sm-0 text-center">
    </div>
    <div class="col-md-4 mt-3 mt-sm-0 text-center text-md-right">
        @livewire('partners.total-income-from-partner', ['partnerTypeCode' => 'donatur'])
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        @livewire('partners.books-count')
    </div>
    <div class="col-md-4">
        @livewire('partners.level-stats', ['partnerTypeCode' => 'donatur'])
    </div>
    <div class="col-md-4">
        @livewire('partners.gender-stats', ['partnerTypeCode' => 'donatur'])
    </div>
</div>
@livewire('partners.income-from-partner-graph', ['partnerTypeCode' => 'donatur'])