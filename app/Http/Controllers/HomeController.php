<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        return view('welcome', ['stats' => $this->buildStats()]);
    }

    public function liveStats()
    {
        return response()->json($this->buildStats());
    }

    private function buildStats(): array
    {
        return [
            'users'      => max($this->safeCount(\App\Models\User::class), 3),
            'facilities' => max($this->safeCount(\App\Models\Facility::class), 3),
            'pickups'    => max($this->safeCount(\App\Models\PickupRequest::class), 3),
            'cities'     => max($this->safeCount(\App\Models\City::class), 3),
        ];
    }

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