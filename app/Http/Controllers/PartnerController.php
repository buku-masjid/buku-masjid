<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Partner;
use App\Rules\PhoneNumberRule;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view-any', new Partner);

        $partnerTypes = (new Partner)->getAvailableTypes();
        $defaultTypeCode = collect($partnerTypes)->keys()->first();
        $request->merge([
            'type_code' => $request->get('type_code', $defaultTypeCode),
        ]);
        $selectedTypeCode = $request->get('type_code');
        $partnerLevels = (new Partner)->getAvailableLevels($selectedTypeCode);
        $selectedTypeName = $partnerTypes[$selectedTypeCode] ?? __('partner.partner');
        $partners = $this->getPartners($request);
        $genders = [
            'm' => __('app.gender_male'),
            'f' => __('app.gender_female'),
        ];
        $availableWorks = [];

        return view('partners.index', compact(
            'partners', 'partnerTypes', 'selectedTypeCode', 'selectedTypeName', 'partnerLevels',
            'genders', 'availableWorks'
        ));
    }

    public function create(Request $request)
    {
        $partnerTypes = (new Partner)->getAvailableTypes();
        $defaultTypeCode = collect($partnerTypes)->keys()->first();
        $request->merge([
            'type_code' => $request->get('type_code', $defaultTypeCode),
        ]);
        $selectedTypeCode = $request->get('type_code');
        $selectedTypeName = $partnerTypes[$selectedTypeCode] ?? __('partner.partner');
        $partnerLevels = (new Partner)->getAvailableLevels($request->get('type_code'));
        $genders = [
            'm' => __('app.gender_male'),
            'f' => __('app.gender_female'),
        ];

        return view('partners.create', compact('partnerLevels', 'selectedTypeCode', 'selectedTypeName', 'genders'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', new Partner);

        $newPartner = $request->validate([
            'name' => ['required', 'max:60'],
            'type_code' => ['required', 'max:30'],
            'gender_code' => ['nullable', 'in:m,f'],
            'phone' => ['nullable', 'max:60', new PhoneNumberRule()],
            'pob' => ['nullable', 'max:255'],
            'dob' => ['nullable', 'date_format:Y-m-d'],
            'address' => ['nullable', 'max:255'],
            'rt' => ['nullable', 'max:3'],
            'rw' => ['nullable', 'max:3'],
            'description' => ['nullable', 'max:255'],
            'level_code' => ['nullable', 'max:30'],
            'religion_id' => ['nullable'],
            'work_id' => ['nullable'],
            'work' => 'nullable', 'max:60',
            'marital_status_id' => ['nullable'],
            'financial_status_id' => ['nullable'],
            'activity_status_id' => ['nullable'],
        ]);
        $newPartner['creator_id'] = auth()->id();

        $partner = Partner::create($newPartner);

        flash(__('partner.created', ['type' => $partner->type]), 'success');

        return redirect()->route('partners.index', ['type_code' => $newPartner['type_code']]);
    }

    public function show(Partner $partner)
    {
        $this->authorize('view', $partner);

        $defaultStartDate = date('Y').'-01-01';
        $startDate = request('start_date', $defaultStartDate);
        $endDate = request('end_date', date('Y-m-d'));
        $availableBooks = Book::orderBy('name')->pluck('name', 'id')->toArray();

        $transactions = $this->getPartnerTransactions($partner, [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'query' => request('query'),
            'book_id' => request('book_id'),
        ]);
        $largestTransaction = $partner->transactions()->orderBy('amount', 'desc')->first();

        return view('partners.show', compact(
            'partner', 'startDate', 'endDate', 'transactions', 'largestTransaction', 'availableBooks'
        ));
    }

    public function edit(Request $request, Partner $partner)
    {
        $this->authorize('update', $partner);

        $partner->loadSum('transactions', 'amount');
        $partnerLevels = (new Partner)->getAvailableLevels($partner->type_code);
        $genders = [
            'm' => __('app.gender_male'),
            'f' => __('app.gender_female'),
        ];

        return view('partners.edit', compact('partner', 'partnerLevels', 'genders'));
    }

    public function update(Request $request, Partner $partner)
    {
        $this->authorize('update', $partner);

        $partnerData = $request->validate([
            'name' => ['required', 'max:60'],
            'type_code' => ['required', 'max:30'],
            'gender_code' => ['nullable', 'in:m,f'],
            'phone' => ['nullable', 'max:60', new PhoneNumberRule()],
            'pob' => ['nullable', 'max:255'],
            'dob' => ['nullable', 'date_format:Y-m-d'],
            'address' => ['nullable', 'max:255'],
            'rt' => ['nullable', 'max:3'],
            'rw' => ['nullable', 'max:3'],
            'description' => ['nullable', 'max:255'],
            'level_code' => ['nullable', 'max:30'],
            'religion_id' => ['nullable'],
            'work_id' => ['nullable'],
            'work' => ['nullable', 'max:60'],
            'marital_status_id' => ['nullable'],
            'financial_status_id' => ['nullable'],
            'activity_status_id' => ['nullable'],
            'is_active' => ['required', 'in:0,1'],
        ]);

        $partner->update($partnerData);

        flash(__('partner.updated', ['type' => $partner->type]), 'success');

        return redirect()->route('partners.show', $partner);
    }

    public function destroy(Partner $partner)
    {
        $this->authorize('delete', $partner);

        request()->validate([
            'partner_id' => 'required',
        ]);

        if (request('partner_id') == $partner->id && $partner->delete()) {
            flash(__('partner.deleted', ['type' => $partner->type]), 'warning');

            return redirect()->route('partners.index', ['type_code' => $partner->type_code]);
        }
        flash(__('partner.undeleted', ['type' => $partner->type]), 'error');

        return back();
    }

    private function getPartnerTransactions(Partner $partner, array $criteria)
    {
        $query = $criteria['query'];
        $endDate = $criteria['end_date'];
        $startDate = $criteria['start_date'];
        $bookId = $criteria['book_id'] ?? null;
        $transactionQuery = $partner->transactions();
        $transactionQuery->whereBetween('date', [$startDate, $endDate]);
        $transactionQuery->when($query, function ($queryBuilder, $query) {
            $queryBuilder->where('description', 'like', '%'.$query.'%');
        });
        $transactionQuery->when($bookId, function ($queryBuilder, $bookId) {
            $queryBuilder->where('book_id', $bookId);
        });

        return $transactionQuery->orderBy('date', 'desc')->with('book')->get();
    }

    private function getPartners(Request $request)
    {
        $partnerQuery = Partner::orderBy('name');
        $partnerQuery->where('type_code', $request->get('type_code'));
        if ($request->get('search_query')) {
            $searchQuery = $request->get('search_query');
            $partnerQuery->where(function ($query) use ($searchQuery) {
                $query->where('name', 'like', '%'.$searchQuery.'%');
                $query->orWhere('phone', 'like', '%'.$searchQuery.'%');
                $query->orWhere('address', 'like', '%'.$searchQuery.'%');
            });
        }
        if ($request->get('gender_code')) {
            $partnerQuery->where('gender_code', $request->get('gender_code'));
        }
        if ($request->get('level_code')) {
            $partnerQuery->where('level_code', $request->get('level_code'));
        }
        if (!is_null($request->get('is_active'))) {
            $partnerQuery->where('is_active', $request->get('is_active'));
        }
        $partners = $partnerQuery->paginate(100);

        return $partners;
    }
}
