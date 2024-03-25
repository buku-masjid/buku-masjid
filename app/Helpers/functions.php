<?php

use Carbon\Carbon;

/**
 * Function helper to add flash notification.
 *
 * @param  null|string  $message  The flashed message.
 * @param  string  $level  Level/type of message
 * @return void
 */
function flash($message = null, $level = 'info')
{
    $session = app('session');
    if (!is_null($message)) {
        $session->flash('flash_notification.message', $message);
        $session->flash('flash_notification.level', $level);
    }
}

/**
 * Format number to string.
 *
 * @return string
 */
function format_number(float $number)
{
    $precision = config('money.precision');
    $decimalSeparator = config('money.decimal_separator');
    $thousandsSeparator = config('money.thousands_separator');

    $number = number_format($number, $precision, $decimalSeparator, $thousandsSeparator);

    return str_replace('-', '- ', $number);
}

function number_step()
{
    $precision = config('money.precision');
    if ($precision == 0) {
        return '1';
    }
    $decimalZero = str_pad('0.', $precision + 1, '0', STR_PAD_RIGHT);

    return $decimalZero.'1';
}

/**
 * Get balance amount based on transactions.
 *
 * @param  string|null  $perDate
 * @param  string|null  $startDate
 * @return float
 */
function balance($perDate = null, $startDate = null, $categoryId = null, $bookId = null)
{
    $transactionQuery = DB::table('transactions');
    if ($perDate) {
        $transactionQuery->where('date', '<=', $perDate);
    }
    if ($startDate) {
        $transactionQuery->where('date', '>=', $startDate);
    }
    if ($categoryId) {
        $transactionQuery->where('category_id', $categoryId);
    }
    if ($bookId) {
        $transactionQuery->where('book_id', $bookId);
    }
    $transactions = $transactionQuery->where('creator_id', auth()->id())->get();

    return $transactions->sum(function ($transaction) {
        return $transaction->in_out ? $transaction->amount : -$transaction->amount;
    });
}

function get_week_numbers(string $year): array
{
    $lastWeekOfTheYear = Carbon::parse($year.'-01-01')->weeksInYear();

    return range(0, $lastWeekOfTheYear);
}

function format_size_units($bytes)
{
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2).' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2).' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2).' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes.' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes.' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;
}

function get_percent($numerator, $denominator)
{
    $formatedString = 0;

    if ($denominator) {
        $formatedString = number_format(($numerator / $denominator * 100), 2);
    }

    return $formatedString;
}
