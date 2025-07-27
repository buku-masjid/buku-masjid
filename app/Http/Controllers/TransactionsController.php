<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transactions\CreateRequest;
use App\Http\Requests\Transactions\UpdateRequest;
use App\Models\BankAccount;
use App\Models\Category;
use App\Models\Partner;
use App\Transaction;
use Facades\App\Helpers\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionsController extends Controller
{
    public function index()
    {
        $editableTransaction = null;
        $yearMonth = $this->getYearMonth();
        $date = request('date');
        $year = (int) request('year', date('Y'));
        $month = request('month', date('m'));
        if (!isset(get_months()[$month])) {
            $month = date('m');
        }
        $startDate = $year.'-'.$month.'-01';

        $transactions = $this->getTansactions($yearMonth);

        $categories = $this->getCategoryList()->prepend('-- '.__('transaction.no_category').' --', 'null');
        $bankAccounts = BankAccount::where('is_active', BankAccount::STATUS_ACTIVE)->pluck('name', 'id')
            ->prepend(__('transaction.cash'), 'null');

        $incomeTotal = $this->getIncomeTotal($transactions);
        $spendingTotal = $this->getSpendingTotal($transactions);

        return view('transactions.index', compact(
            'transactions', 'yearMonth', 'month', 'year', 'categories', 'incomeTotal', 'spendingTotal', 'startDate',
            'date', 'bankAccounts'
        ));
    }

    public function create(Request $request)
    {
        $categories = collect([]);
        $partners = [];
        $partnerTypes = (new Partner)->getAvailableTypes();
        $partnerDefaultValue = __('partner.partner');
        $partnerSelectionLabel = __('partner.partner');
        $originalTransaction = null;
        if ($request->has('original_transaction_id')) {
            $originalTransaction = Transaction::find($request->get('original_transaction_id'));
        }

        if (in_array(request('action'), ['add-income'])) {
            $partnerTypeCodes = Setting::for(auth()->activeBook())->get('income_partner_codes');
            $partnerTypeCodes = json_decode($partnerTypeCodes, true);
            if ($partnerTypeCodes) {
                $partners = $this->getAvailablePartners($partnerTypes, $partnerTypeCodes);
                $partnerDefaultValue = Setting::for(auth()->activeBook())->get('income_partner_null') ?: config('partners.income_default_value');
                $partnerSelectionLabel .= ' (';
                if (count($partnerTypeCodes) > 1) {
                    foreach ($partnerTypes as $partnerTypeCode => $partnerTypeName) {
                        if (in_array($partnerTypeCode, $partnerTypeCodes)) {
                            $partnerSelectionLabel .= $partnerTypeName.', ';
                        }
                    }
                    $partnerSelectionLabel = trim($partnerSelectionLabel, ', ');
                    $partnerSelectionLabel .= ')';
                } else {
                    $partnerSelectionLabel = $partnerTypes[$partnerTypeCodes[0]];
                }
            }
            $categories = Category::orderBy('name')
                ->where('color', config('masjid.income_color'))
                ->where('status_id', Category::STATUS_ACTIVE)
                ->pluck('name', 'id');
        }

        if (in_array(request('action'), ['add-spending'])) {
            $partnerTypeCodes = Setting::for(auth()->activeBook())->get('spending_partner_codes');
            $partnerTypeCodes = json_decode($partnerTypeCodes, true);
            if ($partnerTypeCodes) {
                $partners = $this->getAvailablePartners($partnerTypes, $partnerTypeCodes);
                $partnerDefaultValue = Setting::for(auth()->activeBook())->get('spending_partner_null') ?: config('partners.spending_default_value');
                $partnerSelectionLabel .= ' (';
                if (count($partnerTypeCodes) > 1) {
                    foreach ($partnerTypes as $partnerTypeCode => $partnerTypeName) {
                        if (in_array($partnerTypeCode, $partnerTypeCodes)) {
                            $partnerSelectionLabel .= $partnerTypeName.', ';
                        }
                    }
                    $partnerSelectionLabel = trim($partnerSelectionLabel, ', ');
                    $partnerSelectionLabel .= ')';
                } else {
                    $partnerSelectionLabel = $partnerTypes[$partnerTypeCodes[0]];
                }
            }
            $categories = Category::orderBy('name')
                ->where('color', config('masjid.spending_color'))
                ->where('status_id', Category::STATUS_ACTIVE)
                ->pluck('name', 'id');
        }
        $bankAccounts = BankAccount::where('is_active', BankAccount::STATUS_ACTIVE)->pluck('name', 'id');
        $partnerSettingLink = link_to_route('partners.search', __('settings.settings'), [], ['target' => '_blank']);
        $categorySettingLink = link_to_route('categories.index', __('settings.settings'), [], ['target' => '_blank']);
        $selectedDate = now()->format('Y-m-d');
        if ($request->get('year') && $request->get('month')) {
            $selectedDate = $request->get('year').'-'.$request->get('month').'-'.now()->format('d');
        }

        return view('transactions.create', compact(
            'categories', 'bankAccounts', 'partners', 'partnerTypeCodes', 'partnerTypes', 'partnerDefaultValue',
            'partnerSelectionLabel', 'partnerSettingLink', 'categorySettingLink', 'selectedDate', 'originalTransaction'
        ));
    }

    private function getAvailablePartners(array $partnerTypes, array $partnerTypeCodes): array
    {
        $partners = Partner::where('is_active', BankAccount::STATUS_ACTIVE)
            ->where(function ($query) use ($partnerTypeCodes) {
                foreach ($partnerTypeCodes as $partnerTypeCode) {
                    $query->orWhereJsonContains('type_code', $partnerTypeCode);
                }
            })
            ->orderBy('name')
            ->get();
        if (count($partnerTypeCodes) < 2) {
            return $partners->pluck('name_phone', 'id')->toArray();
        }
        $groupedPartners = $partners->groupBy('type_code');
        $availablePartners = [];
        foreach ($groupedPartners as $typeCode => $partners) {
            if (!isset($partnerTypes[$typeCode])) {
                continue;
            }
            foreach ($partners as $partner) {
                $availablePartners[$partnerTypes[$typeCode]][$partner->id] = $partner->name_phone;
            }
        }

        return $availablePartners;
    }

    public function store(CreateRequest $transactionCreateForm)
    {
        $transaction = $transactionCreateForm->save();

        if ($transaction['in_out']) {
            flash(__('transaction.income_added'), 'success');
        } else {
            flash(__('transaction.spending_added'), 'success');
        }

        return redirect()->route('transactions.index', [
            'month' => $transaction->month, 'year' => $transaction->year,
        ]);
    }

    public function show(Request $request, Transaction $transaction)
    {
        $editableFile = null;
        if (in_array($request->get('action'), ['edit_file'])) {
            $editableFile = $transaction->files()->where('id', $request->get('file_id'))->first();
        }

        return view('transactions.show', compact('transaction', 'editableFile'));
    }

    public function edit(Request $request, Transaction $transaction)
    {
        $this->authorize('manage-transactions', $transaction->book);
        $this->authorize('update', $transaction);

        $categories = $this->getCategoryList();
        $bankAccounts = BankAccount::where('is_active', BankAccount::STATUS_ACTIVE)->pluck('name', 'id');
        $partnerTypes = (new Partner)->getAvailableTypes();
        $partnerDefaultValue = __('partner.partner');
        $partnerSelectionLabel = __('partner.partner');
        $incomePartnerTypeCodes = json_decode(Setting::for($transaction->book)->get('income_partner_codes'), true) ?: [];
        $spendingPartnerTypeCodes = json_decode(Setting::for($transaction->book)->get('spending_partner_codes'), true) ?: [];
        $partnerTypeCodes = array_merge($incomePartnerTypeCodes, $spendingPartnerTypeCodes);
        $partnerSettingLink = link_to_route('partners.search', __('settings.settings'), [], ['target' => '_blank']);
        $categorySettingLink = link_to_route('categories.index', __('settings.settings'), [], ['target' => '_blank']);
        $partners = $this->getAvailablePartners($partnerTypes, $partnerTypeCodes);

        return view('transactions.edit', compact(
            'transaction', 'categories', 'bankAccounts', 'partners', 'partnerTypeCodes', 'partnerTypes', 'partnerDefaultValue',
            'partnerSelectionLabel', 'partnerSettingLink', 'categorySettingLink'
        ));
    }

    public function update(UpdateRequest $transactionUpateForm, Transaction $transaction)
    {
        $transaction = $transactionUpateForm->save();

        flash(__('transaction.updated'), 'success');

        if ($referencePage = $transactionUpateForm->get('reference_page')) {
            if ($referencePage == 'category') {
                if ($transaction->category) {
                    return redirect()->route('categories.show', [
                        $transaction->category_id,
                        'start_date' => $transactionUpateForm->get('start_date'),
                        'end_date' => $transactionUpateForm->get('end_date'),
                        'book_id' => $transactionUpateForm->get('book_id'),
                        'query' => $transactionUpateForm->get('query'),
                    ]);
                }
            }
            if ($referencePage == 'partner') {
                if ($transaction->partner) {
                    return redirect()->route('partners.show', [
                        $transaction->partner_id,
                        'start_date' => $transactionUpateForm->get('start_date'),
                        'end_date' => $transactionUpateForm->get('end_date'),
                        'book_id' => $transactionUpateForm->get('book_id'),
                        'query' => $transactionUpateForm->get('query'),
                    ]);
                }
            }
            if ($referencePage == 'transactions') {
                if ($transaction->category) {
                    return redirect()->route('transactions.index', [
                        'month' => $transaction->month,
                        'year' => $transaction->year,
                        'category_id' => $transactionUpateForm->get('queried_category_id'),
                        'bank_account_id' => $transactionUpateForm->get('queried_bank_account_id'),
                        'query' => $transactionUpateForm->get('query'),
                    ]);
                }
            }
        }

        return redirect()->route('transactions.show', [$transaction]);
    }

    public function destroy(Transaction $transaction)
    {
        $this->authorize('manage-transactions', $transaction->book);
        $this->authorize('delete', $transaction);

        request()->validate(['transaction_id' => 'required']);

        DB::beginTransaction();
        $transaction->files->each->delete();
        $isTransactionDeleted = $transaction->delete();
        DB::commit();

        if (request('transaction_id') == $transaction->id && $isTransactionDeleted) {
            flash(__('transaction.deleted'), 'warning');

            if ($referencePage = request('reference_page')) {
                if ($referencePage == 'category') {
                    return redirect()->route('categories.show', [
                        $transaction->category_id,
                        'start_date' => request('start_date'),
                        'end_date' => request('end_date'),
                        'query' => request('query'),
                    ]);
                }
                if ($referencePage == 'partner') {
                    return redirect()->route('partners.show', [
                        $transaction->partner_id,
                        'start_date' => request('start_date'),
                        'end_date' => request('end_date'),
                        'query' => request('query'),
                    ]);
                }
            }

            return redirect()->route('transactions.index', [
                'month' => $transaction->month, 'year' => $transaction->year,
            ]);
        }

        flash(__('transaction.undeleted'), 'error');

        return back();
    }
}
