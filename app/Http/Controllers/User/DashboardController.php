<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PickupRequest;
use App\Models\EcoCreditTransaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $pickups = PickupRequest::where('user_id', $userId)
            ->with(['facility', 'device'])
            ->latest()
            ->paginate(10);

        $completedPickups = PickupRequest::where('user_id', $userId)
            ->where('status', 'Completed')
            ->count();

        $devicesRecycled = $completedPickups;

        $ecoCreditsBalance = EcoCreditTransaction::where('user_id', $userId)->sum('credits');

        $monthly = EcoCreditTransaction::where('user_id', $userId)
            ->whereYear('created_at', now()->year)
            ->get()
            ->groupBy(fn ($row) => $row->created_at->format('m'))
            ->map(fn ($group) => $group->sum('credits'));

        $chartLabels = [];
        $chartData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthKey = str_pad($m, 2, '0', STR_PAD_LEFT);
            $chartLabels[] = Carbon::create()->month($m)->format('M');
            $chartData[] = (int) ($monthly[$monthKey] ?? 0);
        }

        return view('dashboard', compact(
            'pickups',
            'completedPickups',
            'devicesRecycled',
            'ecoCreditsBalance',
            'chartLabels',
            'chartData'
        ));
    }
}