<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Notification;
use App\Models\SavingsGoal;
use App\Models\Transaction;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function budgets()
    {
        $userId = auth()->id();
        $month = now()->month;
        $year = now()->year;

        $budgets = Budget::with('category')
            ->where('user_id', $userId)
            ->where('month', $month)
            ->where('year', $year)
            ->get();

        $budgets->each(function (Budget $budget) {
            $budget->spent = Transaction::where('user_id', $budget->user_id)
                ->where('category_id', $budget->category_id)
                ->where('type', 'expense')
                ->whereMonth('transaction_date', $budget->month)
                ->whereYear('transaction_date', $budget->year)
                ->sum('amount');

            $budget->progress = $budget->limit_amount > 0
                ? min(100, ($budget->spent / $budget->limit_amount) * 100)
                : 0;
        });

        return view('monitoring.budgets', compact('budgets', 'month', 'year'));
    }

    public function savingsGoals()
    {
        $goals = SavingsGoal::where('user_id', auth()->id())
            ->orderBy('deadline')
            ->get();

        return view('monitoring.savings_goals', compact('goals'));
    }

    public function notifications()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->get();

        return view('monitoring.notifications', compact('notifications'));
    }
}
