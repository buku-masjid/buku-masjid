<?php

use Carbon\Carbon;

/**
 * Get array of date list.
 *
 * @return array
 */
function get_dates()
{
    $dates = [];
    foreach (range(1, 31) as $date) {
        $date = str_pad($date, 2, 0, STR_PAD_LEFT);
        $dates[$date] = $date;
    }

    return $dates;
}

/**
 * Get array of month list.
 *
 * @return array
 */
function get_months()
{
    return [
        '01' => __('time.months.01'),
        '02' => __('time.months.02'),
        '03' => __('time.months.03'),
        '04' => __('time.months.04'),
        '05' => __('time.months.05'),
        '06' => __('time.months.06'),
        '07' => __('time.months.07'),
        '08' => __('time.months.08'),
        '09' => __('time.months.09'),
        '10' => __('time.months.10'),
        '11' => __('time.months.11'),
        '12' => __('time.months.12'),
    ];
}

/**
 * Get array of year list starting from 2018.
 *
 * @return array
 */
function get_years()
{
    $yearRange = range(2020, date('Y'));
    foreach ($yearRange as $year) {
        $years[$year] = $year;
    }

    return $years;
}

/**
 * Get two digits of month.
 *
 * @param  int|string  $number
 * @return string
 */
function month_number($number)
{
    return str_pad($number, 2, '0', STR_PAD_LEFT);
}

/**
 * Get month name from given month number.
 *
 * @param  int|string  $monthNumber
 * @return string
 */
function month_id($monthNumber)
{
    if (is_null($monthNumber)) {
        return $monthNumber;
    }

    $months = get_months();
    $monthNumber = month_number($monthNumber);

    return $months[$monthNumber];
}

function get_date_range_per_week(string $startDate, string $endDate, string $startDay = 'monday'): array
{
    $periode = new \DatePeriod(
        $startDate = Carbon::parse($startDate),
        new \DateInterval('P1D'),
        Carbon::parse($endDate)->addDay()
    );

    $dateRanges = [];
    $dateKey = $startDate->format('Ymd');
    $dateRanges[$dateKey] = [];
    foreach ($periode as $date) {
        if (strtolower($date->format('l')) == $startDay) {
            $dateKey = $date->format('Ymd');
        }
        $dateRanges[$dateKey][] = $date->format('Y-m-d');
    }

    return array_values(array_filter($dateRanges));
}

function get_date_range_text(string $startDate, string $endDate): string
{
    $endDateText = Carbon::parse($endDate)->isoFormat('DD MMM YYYY');
    $startDateText = Carbon::parse($startDate)->isoFormat('DD MMM YYYY');
    if (substr(Carbon::parse($startDate)->isoFormat('DD MMM YYYY'), -4) == substr($endDateText, -4)) {
        $startDateText = Carbon::parse($startDate)->isoFormat('DD MMM');
    }
    if (substr(Carbon::parse($startDate)->isoFormat('DD MMM YYYY'), 3) == substr($endDateText, 3)) {
        $startDateText = Carbon::parse($startDate)->isoFormat('DD');
    }

    return $startDateText.' - '.$endDateText;
}

function get_age_group_date_ranges()
{
    $currentDate = Carbon::now();

    $dateRanges = [];

    foreach (config('partners.age_groups') as $groupName => $ageRange) {
        $startDate = $currentDate->copy();
        $endDate = $currentDate->copy();

        if (is_array($ageRange)) {
            if ($ageRange[0] === '>=') {
                $startDate->subYears($ageRange[1]);
                $endDate = '>=';
            } elseif ($ageRange[0] === '<=') {
                $startDate->subYears($ageRange[1]);
                $endDate = '<=';
            } else {
                $startDate->subYears($ageRange[1]);
                $endDate->subYears($ageRange[0]);
            }
        }

        $dateRanges[$groupName] = [
            in_array($startDate, ['>=', '<=']) ? $startDate : $startDate->format('Y-m-d'),
            in_array($endDate, ['>=', '<=']) ? $endDate : $endDate->format('Y-m-d'),
        ];
    }

    return $dateRanges;
}

/**
 * Parse a date that comes from user input, such as a query string.
 *
 * Only a real calendar date in Y-m-d format is accepted. Anything else
 * (missing, empty, not a string, impossible dates like 2026-02-30,
 * zero dates, relative words like "tomorrow") returns null, so the
 * caller can fall back to its default range.
 *
 * @param  mixed  $value
 */
function parse_date_input($value): ?Carbon
{
    if (!is_string($value) || trim($value) === '') {
        return null;
    }

    $value = trim($value);

    try {
        $date = Carbon::createFromFormat('!Y-m-d', $value);
    } catch (\Throwable $e) {
        return null;
    }

    if (!$date || $date->format('Y-m-d') !== $value) {
        return null;
    }

    return $date;
}

/**
 * Date range that may be opened on the financial report of a book.
 *
 * The window is one year before the earliest transaction and one year after
 * the latest one. The running year window is always included, so a book whose
 * last transaction is already older than a year still renders its default
 * report page.
 *
 * @param  \App\Models\Book  $book
 * @return array{0: Carbon, 1: Carbon} [minAllowedStart, maxAllowedEnd]
 */
function get_report_date_bounds($book): array
{
    $now = Carbon::now();

    $minAllowedStart = $now->copy()->subYear()->startOfDay();
    $maxAllowedEnd = $now->copy()->addYear()->endOfDay();

    if (is_null($book)) {
        return [$minAllowedStart, $maxAllowedEnd];
    }

    $minDate = $book->transactions()->withoutGlobalScope('forActiveBook')->min('date');
    $maxDate = $book->transactions()->withoutGlobalScope('forActiveBook')->max('date');

    if (!is_null($minDate)) {
        $bookMinDate = Carbon::parse($minDate)->subYear()->startOfDay();
        if ($bookMinDate->lt($minAllowedStart)) {
            $minAllowedStart = $bookMinDate;
        }
    }

    if (!is_null($maxDate)) {
        $bookMaxDate = Carbon::parse($maxDate)->addYear()->endOfDay();
        if ($bookMaxDate->gt($maxAllowedEnd)) {
            $maxAllowedEnd = $bookMaxDate;
        }
    }

    return [$minAllowedStart, $maxAllowedEnd];
}

/**
 * Validate the shape of the year/month query parameters.
 *
 * Returns null when either value is not usable, so the caller falls back to
 * its default period instead of handing the value to Carbon.
 *
 * @param  mixed  $year
 * @param  mixed  $month
 */
function parse_year_month_input($year, $month): ?array
{
    if (!is_string($year) && !is_int($year)) {
        return null;
    }

    if (!is_string($month)) {
        return null;
    }

    if (!preg_match('/^\d{4}$/', (string) $year)) {
        return null;
    }

    if ($month !== '00' && !isset(get_months()[$month])) {
        return null;
    }

    return [(int) $year, $month];
}
