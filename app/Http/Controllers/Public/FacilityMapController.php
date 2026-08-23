<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Facility;

class FacilityMapController extends Controller
{
    public function index()
    {
        $facilities = Facility::where('status', 'approved')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with('city')
            ->get();

        return view('public.facilities-map', compact('facilities'));
    }
}