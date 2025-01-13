<?php

namespace App;

use App\Models\BankAccount;
use App\Models\Book;
use App\Models\Category;
use App\Models\File;
use App\Models\Partner;
use App\Traits\Models\ForActiveBook;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    const TYPE_INCOME = 1;
    const TYPE_SPENDING = 0;

    use ForActiveBook;

    protected $fillable = [
        'date', 'amount', 'in_out', 'description', 'partner_id',
        'category_id', 'book_id', 'creator_id', 'bank_account_id',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class)->withDefault(['name' => __('transaction.cash')]);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function getTypeAttribute()
    {
        return $this->in_out ? __('transaction.income') : __('transaction.spending');
    }

    public function getDateAlertAttribute(): string
    {
        if (Carbon::parse($this->date)->lte(now())) {
            return '';
        }

        return '<i class="fe fe-alert-circle text-danger" title="'.__('transaction.forward_date_alert').'"></i>';
    }

    public function getDateOnlyAttribute()
    {
        return substr($this->date, -2);
    }

    public function getMonthAttribute()
    {
        return Carbon::parse($this->date)->format('m');
    }

    public function getMonthNameAttribute()
    {
        return Carbon::parse($this->date)->isoFormat('MMM');
    }

    public function getYearAttribute()
    {
        return Carbon::parse($this->date)->format('Y');
    }

    public function getDayNameAttribute(): string
    {
        if (is_null($this->date)) {
            return '';
        }

        $dayName = Carbon::parse($this->date)->isoFormat('dddd');
        if ($dayName == 'Minggu') {
            $dayName = 'Ahad';
        }

        return $dayName;
    }

    public function getAmountStringAttribute()
    {
        $amountString = format_number($this->amount);

        if ($this->in_out == 0) {
            $amountString = '- '.$amountString;
        }

        return $amountString;
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }
}
