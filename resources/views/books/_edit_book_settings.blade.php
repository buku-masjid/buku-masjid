<div class="row">
    <div class="col-md-6">
        <h4 class="text-primary">{{ __('book.detail') }}</h4>
        {!! FormField::text('name', ['required' => true, 'label' => __('book.name')]) !!}
        {!! FormField::textarea('description', ['label' => __('book.description')]) !!}
        <div class="row">
            <div class="col-md-6">
                {!! FormField::select('bank_account_id', $bankAccounts, [
                    'label' => __('bank_account.bank_account'),
                    'placeholder' => __('book.no_bank_account'),
                ]) !!}
            </div>
            <div class="col-md-6">
                {!! FormField::price('budget', [
                    'label' => __('book.budget'),
                    'type' => 'number',
                    'currency' => config('money.currency_code'),
                    'step' => number_step()
                ]) !!}
            </div>
        </div>
        @can('change-manager', $book)
            {!! FormField::select('manager_id', $financeUsers, [
                'label' => __('book.manager'),
                'placeholder' => __('book.admin_only'),
                'info' => ['text' => __('book.manager_info_text')],
            ]) !!}
        @else
            {!! FormField::textDisplay(__('book.manager'), $book->manager->name) !!}
        @endcan
        {!! FormField::radios('status_id', [
            App\Models\Book::STATUS_INACTIVE => __('book.status_inactive'),
            App\Models\Book::STATUS_ACTIVE => __('app.active')
        ], ['label' => __('app.status')]) !!}
    </div>
    <div class="col-md-6">
        <h4 class="text-primary">{{ __('settings.settings') }}</h4>
        <div class="row">
            <div class="col-md-6">
                {!! FormField::radios('report_visibility_code', [
                    App\Models\Book::REPORT_VISIBILITY_PUBLIC => __('book.report_visibility_public'),
                    App\Models\Book::REPORT_VISIBILITY_INTERNAL => __('book.report_visibility_internal')
                ], ['label' => __('book.report_visibility')]) !!}
            </div>
            <div class="col-md-6">
                {!! FormField::radios('transaction_files_visibility_code', [
                    App\Models\Book::REPORT_VISIBILITY_PUBLIC => __('book.report_visibility_public'),
                    App\Models\Book::REPORT_VISIBILITY_INTERNAL => __('book.report_visibility_internal')
                ], [
                    'value' => Setting::for($book)->get('transaction_files_visibility_code', App\Models\Book::REPORT_VISIBILITY_INTERNAL),
                    'label' => __('book.transaction_files_visibility'),
                ]) !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                {!! FormField::select('report_periode_code', [
                    App\Models\Book::REPORT_PERIODE_IN_MONTHS => __('report.in_months'),
                    App\Models\Book::REPORT_PERIODE_IN_WEEKS => __('report.in_weeks'),
                    App\Models\Book::REPORT_PERIODE_ALL_TIME => __('report.all_time'),
                ], ['label' => __('report.periode'), 'placeholder' => false]) !!}
            </div>
            <div class="col-md-6">
                {!! FormField::select('start_week_day_code', [
                    'monday' => __('time.days.monday'),
                    'tuesday' => __('time.days.tuesday'),
                    'wednesday' => __('time.days.wednesday'),
                    'thursday' => __('time.days.thursday'),
                    'friday' => __('time.days.friday'),
                    'saturday' => __('time.days.saturday'),
                    'sunday' => __('time.days.sunday'),
                ], ['label' => __('report.start_week_day'), 'placeholder' => false]) !!}
            </div>
        </div>
        {!! FormField::text('management_title', [
            'value' => Setting::for($book)->get('management_title'),
            'label' => __('book.management_title'),
            'placeholder' => __('report.management'),
            'info' => ['text' => __('book.management_title_info_text')],
        ]) !!}
        {!! FormField::radios('has_pdf_page_number', [
            '1' => __('app.yes'),
            '0' => __('app.no'),
        ], [
            'value' => Setting::for($book)->get('has_pdf_page_number') == '0' ? '0': '1',
            'label' => __('report.has_pdf_page_number'),
            'placeholder' => false,
        ]) !!}
        <div class="row">
            <div class="col-md-6">
                {!! FormField::checkboxes('income_partner_codes', $partnerTypes, [
                    'value' => json_decode(Setting::for($book)->get('income_partner_codes')),
                    'label' => __('book.income_partners'),
                    'placeholder' => false,
                ]) !!}
                {!! FormField::text('income_partner_null', [
                    'value' => Setting::for($book)->get('income_partner_null'),
                    'label' => __('book.income_partner_null'),
                    'placeholder' => config('partners.income_default_value'),
                ]) !!}
            </div>
            <div class="col-md-6">
                {!! FormField::checkboxes('spending_partner_codes', $partnerTypes, [
                    'value' => json_decode(Setting::for($book)->get('spending_partner_codes')),
                    'label' => __('book.spending_partners'),
                    'placeholder' => false,
                ]) !!}
                {!! FormField::text('spending_partner_null', [
                    'value' => Setting::for($book)->get('spending_partner_null'),
                    'label' => __('book.spending_partner_null'),
                    'placeholder' => config('partners.spending_default_value'),
                ]) !!}
            </div>
        </div>
    </div>
</div>
