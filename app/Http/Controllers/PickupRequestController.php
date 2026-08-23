<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Facility;
use App\Models\PickupRequest;
use Illuminate\Http\Request;

class PickupRequestController extends Controller
{
    public function create(Facility $facility)
    {
        $devices = Device::orderBy('brand')->get();

        return view('pickup-requests.create', compact('facility', 'devices'));
    }

    public function store(Request $request, Facility $facility)
    {
        $validated = $request->validate([
            'device_id'       => 'required|exists:devices,id',
            'pickup_address'  => 'required|string|max:500',
            'preferred_date'  => 'required|date|after_or_equal:today',
            'preferred_time'  => 'required|string|max:50',
            'additional_note' => 'nullable|string|max:500',
        ]);

        PickupRequest::create([
            'user_id'         => auth()->id(),
            'facility_id'     => $facility->id,
            'device_id'       => $validated['device_id'],
            'pickup_address'  => $validated['pickup_address'],
            'preferred_date'  => $validated['preferred_date'],
            'preferred_time'  => $validated['preferred_time'],
            'additional_note' => $validated['additional_note'] ?? null,
            'status'          => 'Requested',
        ]);

        return redirect()
            ->route('facilities.map')
            ->with('success', 'Pickup request sent successfully.');
    }
}