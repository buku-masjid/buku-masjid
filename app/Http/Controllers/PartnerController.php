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
        $selectedTypeCode = $request->get('type_code');
        $partners = Partner::filterBy($request)->orderBy('name')->paginate(100);
        $genders = [
            'm' => __('app.gender_male'),
            'f' => __('app.gender_female'),
        ];

        return view('partners.index', compact(
            'partners', 'partnerTypes', 'genders', 'selectedTypeCode'
        ));
    }

    public function search(Request $request)
    {
        $this->authorize('view-any', new Partner);

        $partnerTypes = (new Partner)->getAvailableTypes();
        $partnerLevels = [];
        if ($request->get('type_code')) {
            $partnerLevels = (new Partner)->getAvailableLevels([$request->get('type_code')]);
        }
        $partners = Partner::filterBy($request)->orderBy('name')->paginate(100);
        $genders = [
            'm' => __('app.gender_male'),
            'f' => __('app.gender_female'),
        ];

        return view('partners.search', compact(
            'partners', 'partnerTypes', 'partnerLevels', 'genders'
        ));
    }

    public function create(Request $request)
    {
        $partnerTypes = (new Partner)->getAvailableTypes();
        $genders = [
            'm' => __('app.gender_male'),
            'f' => __('app.gender_female'),
        ];

        return view('partners.create', compact('partnerTypes', 'genders'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', new Partner);

        $newPartner = $request->validate([
            'name' => ['required', 'max:60'],
            'type_code' => ['required', 'array'],
            'gender_code' => ['nullable', 'in:m,f'],
            'phone' => ['nullable', 'max:60', new PhoneNumberRule],
            'pob' => ['nullable', 'max:255'],
            'dob' => ['nullable', 'date_format:Y-m-d'],
            'address' => ['nullable', 'max:255'],
            'rt' => ['nullable', 'max:3'],
            'rw' => ['nullable', 'max:3'],
            'description' => ['nullable', 'max:255'],
            'level_code' => ['nullable', 'max:30'],
            'religion_id' => ['nullable'],
            'work_type_id' => ['nullable'],
            'work' => 'nullable', 'max:60',
            'marital_status_id' => ['nullable'],
            'financial_status_id' => ['nullable'],
            'activity_status_id' => ['nullable'],
        ]);
        $newPartner['creator_id'] = auth()->id();
        $newPartner['type_code'] = array_values($newPartner['type_code']);

        $partner = Partner::create($newPartner);

        flash(__('partner.created'), 'success');

        return redirect()->route('partners.search');
    }

    public function show(Partner $partner)
    {
        $this->authorize('view', $partner);

        $partnerTypes = $this->getPartnerTypes($partner->type_code);
        $availableLevels = (new Partner)->getAvailableLevels($partner->type_code);
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
            'partner', 'startDate', 'endDate', 'transactions', 'largestTransaction', 'availableBooks', 'partnerTypes',
            'availableLevels'
        ));
    }

    private function getPartnerTypes(array $partnerTypeCodes): array
    {
        $availableTypes = (new Partner)->getAvailableTypes();
        $partnerTypes = collect($availableTypes)->only($partnerTypeCodes)->toArray();

        return $partnerTypes;
    }

    public function edit(Request $request, Partner $partner)
    {
        $this->authorize('update', $partner);

        $partner->loadSum('transactions', 'amount');
        $partnerTypes = (new Partner)->getAvailableTypes();
        $partnerLevels = (new Partner)->getAvailableLevels($partner->type_code);
        $genders = [
            'm' => __('app.gender_male'),
            'f' => __('app.gender_female'),
        ];

        return view('partners.edit', compact('partner', 'partnerTypes', 'partnerLevels', 'genders'));
    }

    public function update(Request $request, Partner $partner)
    {
        $this->authorize('update', $partner);

        $partnerData = $request->validate([
            'name' => ['required', 'max:60'],
            'type_code' => ['required', 'max:30'],
            'gender_code' => ['nullable', 'in:m,f'],
            'phone' => ['nullable', 'max:60', new PhoneNumberRule],
            'pob' => ['nullable', 'max:255'],
            'dob' => ['nullable', 'date_format:Y-m-d'],
            'address' => ['nullable', 'max:255'],
            'rt' => ['nullable', 'max:3'],
            'rw' => ['nullable', 'max:3'],
            'description' => ['nullable', 'max:255'],
            'level_code' => ['nullable', 'max:30'],
            'religion_id' => ['nullable'],
            'work_type_id' => ['nullable'],
            'work' => ['nullable', 'max:60'],
            'marital_status_id' => ['nullable'],
            'financial_status_id' => ['nullable'],
            'activity_status_id' => ['nullable'],
            'is_active' => ['required', 'in:0,1'],
        ]);
        $partnerData['type_code'] = array_values($partnerData['type_code']);

        $partner->update($partnerData);

        flash(__('partner.updated'), 'success');

        return redirect()->route('partners.show', $partner);
    }

    public function destroy(Partner $partner)
    {
        $this->authorize('delete', $partner);

        request()->validate([
            'partner_id' => 'required',
        ]);

        if (request('partner_id') == $partner->id && $partner->delete()) {
            flash(__('partner.deleted'), 'warning');

            return redirect()->route('partners.search');
        }
        flash(__('partner.undeleted'), 'error');

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

    private function getDateRangeByAgeGroupCode(string $ageGroupCode): array
    {
        $ageGroups = get_age_group_date_ranges();

        return isset($ageGroups[$ageGroupCode]) ? $ageGroups[$ageGroupCode] : [];
    }

    public function changeLevels(Request $request, Partner $partner)
    {
        $partnerData = $request->validate([
            'level_code' => ['nullable', 'array'],
            'level_code.*' => ['nullable'],
        ]);

        $partner->update($partnerData);

        flash(__('partner.updated'), 'success');

        return redirect()->route('partners.show', $partner);
    }
}
