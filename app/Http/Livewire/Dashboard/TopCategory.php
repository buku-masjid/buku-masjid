<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class TopCategory extends Component
{
    public $topCategorySummary;
    public $isLoading = true;
    public $startDate;
    public $endDate;
    public $book;
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
        $cacheKey = 'calculateTopCategorySummary_'.$this->startDate.'_'.$this->endDate.'_'.$this->typeCode;
        $duration = now()->addSeconds(10);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        $color = config('masjid.'.$this->typeCode.'_color');
        $topCategorySummary = Category::where('color', $color)
            ->where('book_id', $this->book->id)
            ->withSum(['transactions' => function ($query) {
                $query->whereBetween('date', [$this->startDate, $this->endDate]);
            }], 'amount')
            ->get()
            ->sortByDesc('transactions_sum_amount')
            ->take(5);
        Cache::put($cacheKey, $topCategorySummary, $duration);

        return $topCategorySummary;
    }
}
