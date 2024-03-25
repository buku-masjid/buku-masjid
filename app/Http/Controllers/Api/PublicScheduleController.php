<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lecturing;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PublicScheduleController extends Controller
{
    public function index(Request $request)
    {
        $lecturingQuery = Lecturing::query();
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $lecturingQuery->whereBetween('date', [$startDate, $endDate]);
        $lecturingQuery->orderBy('date')->orderBy('start_time');
        $lecturings = $lecturingQuery->get()->groupBy('audience_code');

        return response()->json($lecturings);
    }
}
