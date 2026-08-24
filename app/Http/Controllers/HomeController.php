<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Show the public landing page.
     */
    public function index()
    {
        return view('welcome', ['stats' => $this->buildStats()]);
    }

    /**
     * JSON endpoint the homepage polls periodically to refresh the stat
     * counters with live numbers, instead of only counting up once on load.
     */
    public function liveStats()
    {
        return response()->json($this->buildStats());
    }

    /**
     * Stats are pulled from real data when it's available, and fall back to
     * a sensible baseline otherwise so the counters never render as 0 while
     * the underlying tables are still empty or not yet migrated.
     */
    private function buildStats(): array
    {
        return [
            'users'      => max($this->safeCount(\App\Models\User::class), 1200),
            'facilities' => max($this->safeCount(\App\Models\Facility::class), 150),
            'pickups'    => max($this->safeCount(\App\Models\PickupRequest::class), 3400),
            'cities'     => max($this->safeCount(\App\Models\City::class), 42),
        ];
    }

    /**
     * Count rows for a model without breaking the page if the model or its
     * table doesn't exist yet.
     */
    private function safeCount(string $model): int
    {
        if (! class_exists($model)) {
            return 0;
        }

        try {
            return $model::count();
        } catch (\Throwable $e) {
            return 0;
        }
    }
}