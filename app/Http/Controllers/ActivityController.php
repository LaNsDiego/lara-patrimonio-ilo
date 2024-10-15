<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityController extends Controller
{
    public function getUserActivities(Request $request)
    {
    
        $userId = (int) $request->user_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
    
        $query = Activity::with('causer');
    
        // Filter by user
        if ($userId) {
            $query->where('causer_id', $userId);
        }
    
        // Filter by range of dates
        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();
            $query->whereBetween('created_at', [$start, $end]);
        }
    
        $activities = $query->orderBy('id','DESC')->get();

        return response()->json($activities);
    }
}
