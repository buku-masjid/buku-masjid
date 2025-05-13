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

function calculate_folder_size(string $absolutePath)
{
    $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';

    if ($isWindows) {
        $cmd = sprintf('powershell -Command "(Get-ChildItem -Path %s -Recurse | Measure-Object -Property Length -Sum).Sum"', escapeshellarg($absolutePath));
    } else {
        $cmd = sprintf('du -sk %s | cut -f1', escapeshellarg($absolutePath));
    }

    $output = trim(shell_exec($cmd));

    if (!is_numeric($output)) {
        return false;
    }

    if (!$isWindows) {
        $output = (int) $output * 1024;
    }

    return (int) $output;
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

// Ref: https://stackoverflow.com/a/11807179
function convert_to_bytes(string $from): ?int
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
    $number = substr($from, 0, -2);
    $suffix = strtoupper(substr($from, -2));

    // B or no suffix
    if (is_numeric(substr($suffix, 0, 1))) {
        return null;
    }

    $exponent = array_flip($units)[$suffix] ?? null;
    if ($exponent === null) {
        return null;
    }

    return $number * (1024 ** $exponent);
}

function get_percent($numerator, $denominator)
{
    $formatedString = 0;

    if ($denominator) {
        $formatedString = number_format(($numerator / $denominator * 100), 2);
    }

    return $formatedString;
}
