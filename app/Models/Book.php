<?php

namespace App\Models;

use App\Traits\Models\ConstantsGetter;
use App\Transaction;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use ConstantsGetter;

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const REPORT_VISIBILITY_PUBLIC = 'public';
    const REPORT_VISIBILITY_INTERNAL = 'internal';
    const REPORT_PERIODE_IN_MONTHS = 'in_months';
    const REPORT_PERIODE_IN_WEEKS = 'in_weeks';
    const REPORT_PERIODE_ALL_TIME = 'all_time';

    protected $fillable = [
        'name', 'description', 'status_id', 'creator_id', 'bank_account_id', 'report_visibility_code', 'report_titles',
        'budget', 'report_periode_code', 'start_week_day_code', 'manager_id',
    ];
    protected $casts = [
        'report_titles' => 'array',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class)->withDefault(['name' => __('app.system')]);
    }

    public function manager()
    {
        return $this->belongsTo(User::class)->withDefault(['name' => __('book.admin_only')]);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function getNameLabelAttribute()
    {
        return '<span class="badge badge-pill badge-secondary">'.$this->name.'</span>';
    }

    public function getStatusAttribute()
    {
        return $this->status_id == static::STATUS_INACTIVE ? __('app.inactive') : __('app.active');
    }

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class)->withDefault(['name' => __('book.no_bank_account')]);
    }

    public function getBalance($perDate = null, $startDate = null, $categoryId = null, $bankAccountId = null): float
    {
        $transactionQuery = $this->transactions();
        $transactionQuery->withoutGlobalScope('forActiveBook');
        if ($perDate) {
            $transactionQuery->where('date', '<=', $perDate);
        }
        if ($startDate) {
            $transactionQuery->where('date', '>=', $startDate);
        }
        if ($categoryId) {
            $transactionQuery->where('category_id', $categoryId);
        }
        if ($bankAccountId) {
            $transactionQuery->where('bank_account_id', $bankAccountId);
        }
        $transactionQuery->where('book_id', $this->id);
        $transactions = $transactionQuery->get();

        return $transactions->sum(function ($transaction) {
            return $transaction->in_out ? $transaction->amount : -$transaction->amount;
        });
    }

    public function getIncomeTotalAttribute()
    {
        $transactionQuery = $this->transactions();
        $transactionQuery->withoutGlobalScope('forActiveBook');
        $transactionQuery->where('in_out', 1);
        $transactionQuery->where('book_id', $this->id);
        $transactions = $transactionQuery->get();

        return $transactions->sum('amount');
    }

    public function getProgressPercentAttribute()
    {
        if (is_null($this->budget)) {
            return 0;
        }

        if ($this->budget == 0) {
            return 100;
        }

        return $this->income_total / $this->budget * 100;
    }

    public function getProgressPercentColorAttribute()
    {
        $progressPercent = $this->progress_percent;
        if ($progressPercent > 75) {
            return 'success';
        }
        if ($progressPercent > 50) {
            return 'info';
        }
        if ($progressPercent > 25) {
            return 'warning';
        }

        return 'danger';
    }

    public function getNonceAttribute()
    {
        return sha1($this->id.config('app.key'));
    }
}
