<?php

namespace App\Http\Controllers;

use App\Models\FocusSession;
use App\Models\Streak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function stats()
    {
        $userId = Auth::id();

        // إحصائيات الأشجار
        $todayTrees = FocusSession::where('user_id', $userId)
            ->where('success', true)
            ->whereDate('session_date', today())
            ->count();

        $weekTrees = FocusSession::where('user_id', $userId)
            ->where('success', true)
            ->whereBetween('session_date', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        $monthTrees = FocusSession::where('user_id', $userId)
            ->where('success', true)
            ->whereMonth('session_date', now()->month)
            ->whereYear('session_date', now()->year)
            ->count();

        $yearTrees = FocusSession::where('user_id', $userId)
            ->where('success', true)
            ->whereYear('session_date', now()->year)
            ->count();

        // الستريك
        $streak = Streak::where('user_id', $userId)->first();

        // إحصائيات الوقت
        $totalMinutes = FocusSession::where('user_id', $userId)
            ->where('success', true)
            ->sum('duration');

        return response()->json([
            'trees' => [
                'today' => $todayTrees,
                'week' => $weekTrees,
                'month' => $monthTrees,
                'year' => $yearTrees,
                'all_time' => FocusSession::where('user_id', $userId)->where('success', true)->count()
            ],
            'streak' => [
                'current' => $streak ? $streak->current_streak : 0,
                'longest' => $streak ? $streak->longest_streak : 0
            ],
            'total_minutes' => $totalMinutes,
            'last_session' => FocusSession::where('user_id', $userId)
                ->latest()
                ->first()
        ]);
    }
}
