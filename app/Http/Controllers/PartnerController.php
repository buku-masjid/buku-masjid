<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Partner;
use App\Transaction;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PartnerController extends Controller
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
        $partnerLevelStats = $this->getPartnerLevelStats($selectedTypeCode, $partnerLevels);
        $selectedTypeName = $partnerTypes[$selectedTypeCode] ?? __('partner.partner');
        $partners = $this->getPartners($request);
        if (in_array(request('action'), ['edit', 'delete']) && request('id') != null) {
            $editablePartner = Partner::find(request('id'));
        }
        $genders = [
            'm' => __('app.gender_male'),
            'f' => __('app.gender_female'),
        ];
        $partnerGenderStats = $this->getPartnerGenderStats($selectedTypeCode, $genders);
        $partnerTotalIncome = $this->getPartnerTransactionTotal($selectedTypeCode, 1);
        $partnerTotalSpending = $this->getPartnerTransactionTotal($selectedTypeCode, 0);
        $booksCount = Book::count();
        $partnerMonthlySummary = $this->calculatePartnerMonthlySummary($selectedTypeCode, 1);
        $partnerMonthlySeries = $this->parsePartnerMonthlySeries($partnerMonthlySummary);

        return view('partners.index', compact(
            'partners', 'editablePartner', 'partnerTypes', 'selectedTypeCode', 'selectedTypeName', 'partnerLevels',
            'genders', 'partnerTotalIncome', 'partnerTotalSpending', 'booksCount', 'partnerLevelStats', 'partnerGenderStats',
            'partnerMonthlySummary', 'partnerMonthlySeries'
        ));
    }

    private function calculatePartnerMonthlySummary(string $partnerType, int $inOut): Collection
    {
        $rawSelect = "count(id) as transactions_count";
        $rawSelect = "sum(amount) as total";
        $rawSelect .= ", year(date) as transaction_year";
        $rawSelect .= ", month(date) as transaction_month";
        $partnerMonthlySummary = DB::table('transactions')->selectRaw($rawSelect)
            ->whereExists(function (Builder $query) use ($partnerType) {
                $query->select(DB::raw(1))
                    ->from('partners')
                    ->whereColumn('transactions.partner_id', 'partners.id')
                    ->where('partners.type_code', $partnerType);
            })
            ->where('in_out', $inOut)
            ->groupBy('transaction_year')
            ->groupBy('transaction_month')
            ->get();
        $partnerMonthlySummary = $partnerMonthlySummary->each(function ($monthlySummary) {
            $monthlySummary->month_string = str_pad($monthlySummary->transaction_month, 2, '0', STR_PAD_LEFT);
            return $monthlySummary;
        });

        return $partnerMonthlySummary;
    }

    private function parsePartnerMonthlySeries(Collection $partnerMonthlySummary): array
    {
        $availableYears = $partnerMonthlySummary->pluck('transaction_year')->unique();
        $series = [];
        foreach ($availableYears as $year) {
            $serie = [
                'name' => $year,
                'type' => 'line',
                'data' => [],
            ];
            foreach (get_months() as $monthNumber => $monthName) {
                $monthlySummary = $partnerMonthlySummary->filter(function ($monthlySummary) use ($year, $monthNumber) {
                    return $monthlySummary->transaction_year == $year && $monthlySummary->month_string == $monthNumber;
                })->first();
                $serie['data'][] = $monthlySummary ? (float) $monthlySummary->total : 0;
            }
            $series[] = $serie;
        }

        return $series;
    }

    public function store(Request $request)
    {
        $this->authorize('create', new Partner);

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

        return redirect()->route('partners.index', ['type_code' => $newPartner['type_code']]);
    }

    public function show(Partner $partner)
    {
        $this->authorize('view', $partner);

        $defaultStartDate = date('Y-m').'-01';
        $startDate = request('start_date', $defaultStartDate);
        $endDate = request('end_date', date('Y-m-d'));

        $transactions = $this->getPartnerTransactions($partner, [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'query' => request('query'),
        ]);

        return view('partners.show', compact('partner', 'startDate', 'endDate', 'transactions'));
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

        return redirect()->route('partners.index', ['type_code' => $partnerData['type_code']]);
    }

    public function destroy(Partner $partner)
    {
        $this->authorize('delete', $partner);

        request()->validate([
            'partner_id' => 'required',
        ]);

        if (request('partner_id') == $partner->id && $partner->delete()) {
            flash(__('partner.deleted', ['type' => $partner->type]), 'warning');

            return redirect()->route('partners.index');
        }
        flash(__('partner.undeleted', ['type' => $partner->type]), 'error');

        return back();
    }

    private function getPartnerTransactions(Partner $partner, array $criteria)
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

    private function getPartnerTransactionTotal(string $partnerType, int $inOut): float
    {
        $amount = Transaction::withoutGlobalScope('forActiveBook')
            ->whereHas('partner', function ($query) use ($partnerType) {
                $query->where('type_code', $partnerType);
            })
            ->where('in_out', $inOut)
            ->sum('amount');

        return (float) $amount;
    }

    private function getPartnerLevelStats(string $typeCode, array $partnerLevels): array
    {
        $partnerLevelStats = [];
        $partnerTotal = Partner::where('type_code', $typeCode)->count();
        foreach ($partnerLevels as $partnerLevelCode => $partnerLevelName) {
            $partnerLevelCount = Partner::where('type_code', $typeCode)->where('level_code', $partnerLevelCode)->count();
            $partnerLevelPercent = get_percent($partnerLevelCount, $partnerTotal);
            $partnerLevelStats[$partnerLevelName.'&nbsp;&nbsp;&nbsp;&nbsp;<strong>'.$partnerLevelCount.'</strong> ('.$partnerLevelPercent.'%)'] = $partnerLevelCount;
        }

        return $partnerLevelStats;
    }

    private function getPartnerGenderStats(string $typeCode, array $genders): array
    {
        $partnerGenderStats = [];
        $partnerTotal = Partner::where('type_code', $typeCode)->count();
        foreach ($genders as $genderCode => $genderName) {
            $partnerGenderCount = Partner::where('type_code', $typeCode)->where('gender_code', $genderCode)->count();
            $partnerGenderPercent = get_percent($partnerGenderCount, $partnerTotal);
            $partnerGenderStats[$genderName.'&nbsp;&nbsp;&nbsp;&nbsp;<strong>'.$partnerGenderCount.'</strong> ('.$partnerGenderPercent.'%)'] = $partnerGenderCount;
        }

        return $partnerGenderStats;
    }
}
