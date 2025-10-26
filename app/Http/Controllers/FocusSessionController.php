<?php

namespace App\Http\Controllers;

use App\Models\FocusSession;
use App\Models\Streak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FocusSessionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'duration' => 'required|integer|min:1',
            'success' => 'required|boolean'
        ]);

        $session = FocusSession::create([
            'user_id' => Auth::id(),
            'duration' => $request->duration,
            'success' => $request->success,
            'session_date' => now()
        ]);

        // تحديث الستريك
        $this->updateStreak(Auth::id());

        return response()->json($session, 201);
    }

    public function index(Request $request)
    {
        $period = $request->get('period', 'day'); // day, week, month, year

        $query = FocusSession::where('user_id', Auth::id())
            ->where('success', true);

        switch($period) {
            case 'day':
                $query->whereDate('session_date', today());
                break;
            case 'week':
                $query->whereBetween('session_date', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('session_date', now()->month)
                      ->whereYear('session_date', now()->year);
                break;
            case 'year':
                $query->whereYear('session_date', now()->year);
                break;
        }

        $sessions = $query->get();
        $totalTrees = $sessions->count();
        $totalMinutes = $sessions->sum('duration');

        return response()->json([
            'total_trees' => $totalTrees,
            'total_minutes' => $totalMinutes,
            'sessions' => $sessions
        ]);
    }

    private function updateStreak($userId)
    {
        $streak = Streak::firstOrCreate(
            ['user_id' => $userId],
            ['current_streak' => 0, 'longest_streak' => 0]
        );

        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        if (!$streak->last_session_date) {
            // أول جلسة للمستخدم
            $streak->current_streak = 1;
        } elseif ($streak->last_session_date->isToday()) {
            // نفس اليوم - لا تحديث
            return;
        } elseif ($streak->last_session_date->equalTo($yesterday)) {
            // يوم متتالي - زيادة الستريك
            $streak->current_streak++;
        } else {
            // كسر الستريك
            $streak->current_streak = 1;
        }

        // تحديث أطول ستريك
        if ($streak->current_streak > $streak->longest_streak) {
            $streak->longest_streak = $streak->current_streak;
        }

        $streak->last_session_date = $today;
        $streak->save();
    }
}
