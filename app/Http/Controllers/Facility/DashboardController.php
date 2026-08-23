<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\FacilityRequest;

class DashboardController extends Controller
{
    public function index()
    {
        $facility = Facility::where('user_id', auth()->id())->first();

        // Approved facility mil gayi -> dashboard dikhao
        if ($facility) {
            $pickupsCount   = $facility->pickupRequests()->count();
            $pendingPickups = $facility->pickupRequests()->where('status', 'Requested')->count();

            return view('facility.dashboard', compact('facility', 'pickupsCount', 'pendingPickups'));
        }

        // Facility nahi mili, to request check karo
        $facilityRequest = FacilityRequest::where('user_id', auth()->id())->latest()->first();

        if (!$facilityRequest) {
            return redirect()->route('facility.create');
        }

        // pending / rejected request
        return view('facility.pending', compact('facilityRequest'));
    }
}