<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;

class FacilityController extends Controller
{
    public function index()
    {
        $facilities = Facility::withCount('pickupRequests')->with('city')->latest()->paginate(10);
        return view('admin.facilities.index', compact('facilities'));
    }

    public function edit($id)
    {
        $facility = Facility::findOrFail($id);
        return view('admin.facilities.edit', compact('facility'));
    }

    public function update(\Illuminate\Http\Request $request, $id)
    {
        $facility = Facility::findOrFail($id);
        $facility->update($request->only([
            'facility_name', 'contact_person', 'email', 'area', 'full_address',
            'accepted_ewaste', 'pickup_availability', 'working_hours', 'status'
        ]));
        return back()->with('success', 'Facility updated successfully!');
    }

    public function destroy($id)
    {
        Facility::findOrFail($id)->delete();
        return back()->with('success', 'Facility removed.');
    }
}