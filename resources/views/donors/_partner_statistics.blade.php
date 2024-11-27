<div class="row" style="min-height: 8em;">
    <div class="col-md-4 text-center text-md-left">
        <h1 class="page-title">{{ __('partner.partner_type_donor') }}</h1>
        <div class="page-subtitle ml-0">
            {{ __('transaction.income') }} {{ __('donor.donor') }} {{ Setting::get('masjid_name') }}.
        </div>
    </div>
    <div class="col-md-4 mt-3 mt-sm-0 text-center">
        @can('create', new App\Transaction)
            {{ link_to_route('donor_transactions.create', __('donor.add_donation'), [], ['class' => 'btn btn-success']) }}
        @endcan
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
