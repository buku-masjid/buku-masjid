<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class TopCategory extends Component
{
    public $topCategorySummary;
    public $isLoading = true;
    public $year;
    public $typeCode;

    public function render()
    {
        return view('livewire.dashboard.top_category');
    }

    public function getTopCategorySummary()
    {
        $this->topCategorySummary = $this->calculateTopCategorySummary();
        $this->isLoading = false;
    }

    private function calculateTopCategorySummary()
    {
        $cacheKey = 'calculateTopCategorySummary_'.$this->year;
        $duration = now()->addSeconds(10);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        $color = config('masjid.'.$this->typeCode.'_color');
        $topCategorySummary = Category::where('color', $color)->withSum(['transactions' => function ($query) {
            $query->whereYear('date', $this->year);
        }], 'amount')->get()->sortByDesc('transactions_sum_amount');
        Cache::put($cacheKey, $topCategorySummary, $duration);

        return $topCategorySummary;
    }
}
