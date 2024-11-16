<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;

class DonorController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view-any', new Partner);

        $editablePartner = null;
        $partnerTypes = (new Partner)->getAvailableTypes();
        $defaultTypeCode = collect($partnerTypes)->keys()->first();
        $request->merge([
            'type_code' => $request->get('type_code', $defaultTypeCode),
        ]);
        $selectedTypeCode = $request->get('type_code');
        $partnerLevels = (new Partner)->getAvailableLevels($selectedTypeCode);
        $selectedTypeName = $partnerTypes[$selectedTypeCode] ?? __('partner.partner');
        $partners = $this->getDonors($request);
        if (in_array(request('action'), ['edit', 'delete']) && request('id') != null) {
            $editablePartner = Partner::find(request('id'));
        }
        $genders = [
            'm' => __('app.gender_male'),
            'f' => __('app.gender_female'),
        ];

        return view('donors.index', compact(
            'partners', 'editablePartner', 'partnerTypes', 'selectedTypeCode', 'selectedTypeName', 'partnerLevels',
            'genders'
        ));
    }

    public function store(Request $request)
    {
        $this->authorize('create', new Donor);

        $newPartner = $request->validate([
            'name' => 'required|max:60',
            'type_code' => 'required|max:30',
            'level_code' => 'nullable|max:30',
            'gender_code' => 'nullable|in:m,f',
            'phone' => 'nullable|max:60',
            'work' => 'nullable|max:60',
            'address' => 'nullable|max:255',
            'description' => 'nullable|max:255',
        ]);
        $newPartner['creator_id'] = auth()->id();

        $partner = Partner::create($newPartner);

        flash(__('partner.created', ['type' => $partner->type]), 'success');

        return redirect()->route('donors.index', ['type_code' => $newPartner['type_code']]);
    }

    public function show(Partner $partner)
    {
        $this->authorize('view', $partner);

        $defaultStartDate = date('Y').'-01-01';
        $startDate = request('start_date', $defaultStartDate);
        $endDate = request('end_date', date('Y-m-d'));

        $transactions = $this->getDonorTransactions($partner, [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'query' => request('query'),
        ]);
        $largestTransaction = $partner->transactions()->orderBy('amount', 'desc')->first();

        return view('donors.show', compact('partner', 'startDate', 'endDate', 'transactions', 'largestTransaction'));
    }

    public function update(Request $request, Partner $partner)
    {
        $this->authorize('update', $partner);

        $partnerData = $request->validate([
            'name' => 'required|max:60',
            'type_code' => 'required|max:30',
            'level_code' => 'nullable|max:30',
            'phone' => 'nullable|max:60',
            'work' => 'nullable|max:60',
            'address' => 'nullable|max:255',
            'description' => 'nullable|max:255',
            'is_active' => 'required|in:0,1',
        ]);

        $partner->update($partnerData);

        flash(__('partner.updated', ['type' => $partner->type]), 'success');

        return redirect()->route('donors.index', ['type_code' => $partnerData['type_code']]);
    }

    public function destroy(Partner $partner)
    {
        $this->authorize('delete', $partner);

        request()->validate([
            'partner_id' => 'required',
        ]);

        if (request('partner_id') == $partner->id && $partner->delete()) {
            flash(__('partner.deleted', ['type' => $partner->type]), 'warning');

            return redirect()->route('donors.index');
        }
        flash(__('partner.undeleted', ['type' => $partner->type]), 'error');

        return back();
    }

    private function getDonorTransactions(Partner $partner, array $criteria)
    {
        $query = $criteria['query'];
        $endDate = $criteria['end_date'];
        $startDate = $criteria['start_date'];

        $transactionQuery = $partner->transactions();
        $transactionQuery->whereBetween('date', [$startDate, $endDate]);
        $transactionQuery->when($query, function ($queryBuilder, $query) {
            $queryBuilder->where('description', 'like', '%'.$query.'%');
        });

        return $transactionQuery->orderBy('date', 'desc')->with('book')->get();
    }

    private function getDonors(Request $request)
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
        $partners = $partnerQuery->withSum('transactions', 'amount')->paginate(100);

        return $partners;
    }
}
