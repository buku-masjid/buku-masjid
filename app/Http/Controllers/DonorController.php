<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Partner;
use App\Rules\PhoneNumberRule;
use Illuminate\Http\Request;

class DonorController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view-any', new Partner);

        $availableBooks = Book::orderBy('name')->pluck('name', 'id')->toArray();
        $selectedMonth = $request->get('month', today()->format('m'));
        $selectedYear = $request->get('year', today()->format('Y'));
        $selectedBookId = $request->get('book_id');
        $selectedBook = Book::find($request->get('book_id'));

        return view('donors.index', compact(
            'availableBooks', 'selectedMonth', 'selectedYear', 'selectedBookId', 'selectedBook'
        ));
    }

    public function search(Request $request)
    {
        $this->authorize('view-any', new Partner);

        $partnerLevels = (new Partner)->getAvailableLevels(['donatur']);
        $request->merge(['type_code' => 'donatur']);
        $partners = Partner::filterBy($request)->orderBy('name')->withSum('transactions', 'amount')->paginate(100);
        $genders = [
            'm' => __('app.gender_male'),
            'f' => __('app.gender_female'),
        ];

        return view('donors.search', compact(
            'partners', 'partnerLevels', 'genders'
        ));
    }

    public function create()
    {
        $partnerLevels = (new Partner)->getAvailableLevels(['donatur']);
        $genders = [
            'm' => __('app.gender_male'),
            'f' => __('app.gender_female'),
        ];

        return view('donors.create', compact('partnerLevels', 'genders'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', new Partner);

        $newPartner = $request->validate([
            'name' => 'required|max:60',
            'level_code' => 'nullable|max:30',
            'gender_code' => 'nullable|in:m,f',
            'phone' => ['nullable', 'max:60', new PhoneNumberRule],
            'work' => 'nullable|max:60',
            'address' => 'nullable|max:255',
            'description' => 'nullable|max:255',
        ]);
        $newPartner['type_code'] = ['donatur'];
        if ($newPartner['level_code']) {
            $newPartner['level_code'] = [
                'donatur' => $newPartner['level_code'],
            ];
        }
        $newPartner['creator_id'] = auth()->id();

        $partner = Partner::create($newPartner);

        flash(__('partner.created'), 'success');

        return redirect()->route('donors.search');
    }

    public function show(Partner $partner)
    {
        $this->authorize('view', $partner);

        $defaultStartDate = date('Y').'-01-01';
        $startDate = request('start_date', $defaultStartDate);
        $endDate = request('end_date', date('Y-m-d'));
        $availableBooks = Book::orderBy('name')->pluck('name', 'id')->toArray();

        $transactions = $this->getDonorTransactions($partner, [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'query' => request('query'),
            'book_id' => request('book_id'),
        ]);
        $largestTransaction = $partner->transactions()->orderBy('amount', 'desc')->first();

        return view('donors.show', compact(
            'partner', 'startDate', 'endDate', 'transactions', 'largestTransaction', 'availableBooks'
        ));
    }

    public function edit(Partner $partner)
    {
        $this->authorize('update', $partner);

        $partner->loadSum('transactions', 'amount');
        $partnerLevels = (new Partner)->getAvailableLevels(['donatur']);
        $selectedPartnerLevel = $partner->level_code['donatur'] ?? null;
        $genders = [
            'm' => __('app.gender_male'),
            'f' => __('app.gender_female'),
        ];

        return view('donors.edit', compact('partner', 'partnerLevels', 'genders', 'selectedPartnerLevel'));
    }

    public function update(Request $request, Partner $partner)
    {
        $this->authorize('update', $partner);

        $partnerData = $request->validate([
            'name' => 'required|max:60',
            'level_code' => 'nullable|max:30',
            'phone' => ['nullable', 'max:60', new PhoneNumberRule],
            'work' => 'nullable|max:60',
            'address' => 'nullable|max:255',
            'description' => 'nullable|max:255',
            'is_active' => 'required|in:0,1',
        ]);
        if ($partnerData['level_code']) {
            $existingPartnerLevelCode = $partner->level_code ?: [];
            $newPartnerLevelCode = array_merge($existingPartnerLevelCode, [
                'donatur' => $partnerData['level_code'],
            ]);
            $partnerData['level_code'] = $newPartnerLevelCode;
        }

        $partner->update($partnerData);

        flash(__('donor.updated'), 'success');

        return redirect()->route('donors.show', $partner);
    }

    public function destroy(Partner $partner)
    {
        $this->authorize('delete', $partner);

        request()->validate([
            'partner_id' => 'required',
        ]);

        if (request('partner_id') == $partner->id && $partner->delete()) {
            flash(__('donor.deleted'), 'warning');

            return redirect()->route('donors.search');
        }
        flash(__('donor.undeleted'), 'error');

        return back();
    }

    private function getDonorTransactions(Partner $partner, array $criteria)
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
}
