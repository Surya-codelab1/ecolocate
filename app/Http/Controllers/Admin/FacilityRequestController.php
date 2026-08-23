<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FacilityRequest;
use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityRequestController extends Controller
{
    public function index()
    {
        $requests = FacilityRequest::with('city')->latest()->paginate(10);

        // Approved facilities already have coordinates — shown as faint context markers
        // so the admin can see if a new request is close to an existing facility.
        $existingFacilities = Facility::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get(['id', 'facility_name', 'latitude', 'longitude']);

        return view('admin.facility-requests.index', compact('requests', 'existingFacilities'));
    }

    public function show($id)
    {
        $request = FacilityRequest::with('city')->findOrFail($id);
        return view('admin.facility-requests.show', compact('request'));
    }

    /**
     * GET /admin/facility-requests/live
     * Polled every few seconds by the map page to pick up brand-new
     * pending requests without a full page refresh.
     */
    public function live()
    {
        $pending = FacilityRequest::with('city')
            ->where('status', 'pending')
            ->latest()
            ->get()
            ->map(fn ($r) => [
                'id' => $r->id,
                'name' => $r->facility_name,
                'contact' => $r->contact_person,
                'address' => $r->full_address . ', ' . $r->area . ', ' . ($r->city->name ?? ''),
                'reviewUrl' => route('admin.facility-requests.show', $r->id),
            ]);

        return response()->json($pending);
    }

    public function approve(Request $req, $id)
    {
        $data = $req->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $facilityRequest = FacilityRequest::findOrFail($id);

        Facility::create([
            'user_id' => $facilityRequest->user_id,
            'city_id' => $facilityRequest->city_id,
            'facility_name' => $facilityRequest->facility_name,
            'contact_person' => $facilityRequest->contact_person,
            'email' => $facilityRequest->email,
            'area' => $facilityRequest->area,
            'full_address' => $facilityRequest->full_address,
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'accepted_ewaste' => $facilityRequest->accepted_ewaste,
            'pickup_availability' => $facilityRequest->pickup_availability,
            'working_hours' => $facilityRequest->working_hours,
            'status' => 'approved',
        ]);

        $facilityRequest->update(['status' => 'approved']);

        return redirect()->route('admin.facility-requests.index')
            ->with('success', 'Facility approved & published successfully!');
    }

    public function reject($id)
    {
        FacilityRequest::findOrFail($id)->update(['status' => 'rejected']);
        return back()->with('success', 'Request rejected.');
    }
}