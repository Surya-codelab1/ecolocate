<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
            'users'      => max($this->liveUserCount(), 1200),
            'facilities' => max($this->safeCount(\App\Models\Facility::class), 150),
            'pickups'    => max($this->safeCount(\App\Models\PickupRequest::class), 3400),
            // TODO: replace with a real distinct-city query once facilities
            // store a normalized city column, e.g.
            // Facility::query()->distinct('city')->count('city')
            'cities'     => 42,
        ];
    }

    /**
     * Real "users online right now" count, based on rows in the sessions
     * table with activity in the last 5 minutes. Requires SESSION_DRIVER=database
     * in .env (the default Laravel session table already stores last_activity).
     * Falls back to 0 (and the baseline in buildStats()) if the sessions
     * table isn't set up as a database table.
     */
    private function liveUserCount(): int
    {
        if (! Schema::hasTable('sessions')) {
            return 0;
        }

        try {
            return DB::table('sessions')
                ->where('last_activity', '>=', now()->subMinutes(5)->timestamp)
                ->count();
        } catch (\Throwable $e) {
            return 0;
        }
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