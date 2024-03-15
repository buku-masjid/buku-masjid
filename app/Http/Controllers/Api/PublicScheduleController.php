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
        $startDate = $request->startDate ? $request->startDate : Carbon::now()->startOfMonth();
        $endDate = $request->endDate ? $request->endDate : Carbon::now()->endOfMonth();
        $lecturingQuery->whereBetween('date', [$startDate, $endDate]);
        $lecturingQuery->orderBy('date')->orderBy('start_time');
        $lecturings = $lecturingQuery->get()->groupBy('audience_code');
        
        return response()->json($lecturings);
    }
}
