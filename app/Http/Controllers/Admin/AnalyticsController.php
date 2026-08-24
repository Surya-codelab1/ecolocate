<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Device;
use App\Models\PickupRequest;
use App\Models\EcoCreditTransaction;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        $facilitiesByCity = Facility::join('cities', 'facilities.city_id', '=', 'cities.id')
            ->select('cities.name', DB::raw('count(facilities.id) as total'))
            ->groupBy('cities.name')
            ->pluck('total', 'name');

        $deviceCategories = Device::select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->pluck('total', 'category');

        $pickupStats = PickupRequest::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');
$ecoCreditsMonthly = EcoCreditTransaction::orderBy('created_at')
    ->get()
    ->groupBy(fn ($row) => $row->created_at->format('m-Y'))
    ->map(fn ($group) => $group->sum('credits'));

        return view('admin.analytics.index', compact(
            'facilitiesByCity', 'deviceCategories', 'pickupStats', 'ecoCreditsMonthly'
        ));
    }
}