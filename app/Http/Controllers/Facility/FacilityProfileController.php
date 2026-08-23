<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use App\Models\FacilityRequest;
use App\Models\Facility;
use App\Models\City;
use Illuminate\Http\Request;

class FacilityProfileController extends Controller
{
    public function create()
    {
        $existing = FacilityRequest::where('user_id', auth()->id())->first();

        if ($existing) {
            return redirect()->route('facility.dashboard');
        }

        $cities = City::orderBy('name')->get();

        return view('facility.create', compact('cities'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);

        FacilityRequest::create([
            'user_id' => auth()->id(),
            ...$validated,
            'status'  => 'pending',
        ]);

        return redirect()
            ->route('facility.dashboard')
            ->with('success', 'Facility request submitted. Waiting for admin approval.');
    }

    public function edit()
    {
        $facility = Facility::where('user_id', auth()->id())->firstOrFail();
        $cities   = City::orderBy('name')->get();

        return view('facility.edit', compact('facility', 'cities'));
    }

    public function update(Request $request)
    {
        $facility   = Facility::where('user_id', auth()->id())->firstOrFail();
        $validated  = $this->validateRequest($request);

        $facility->update($validated);

        return redirect()
            ->route('facility.dashboard')
            ->with('success', 'Facility profile updated successfully.');
    }

    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'facility_name'       => 'required|string|max:255',
            'contact_person'      => 'required|string|max:255',
            'email'               => 'required|email|max:255',
            'city_id'             => 'required|exists:cities,id',
            'area'                => 'required|string|max:255',
            'full_address'        => 'required|string|max:500',
            'accepted_ewaste'     => 'required|string|max:500',
            'pickup_availability' => 'required|string|max:255',
            'working_hours'       => 'required|string|max:255',
        ]);
    }
}