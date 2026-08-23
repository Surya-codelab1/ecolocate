<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Facility;

class FacilityApiController extends Controller
{
    /**
     * GET /api/facilities
     * Returns all approved, published facilities as a plain JSON array —
     * consumed by the mobile app to plot them on its own map.
     */
    public function index()
    {
        $facilities = Facility::with('city')
            ->where('status', 'approved')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->latest()
            ->get()
            ->map(fn ($facility) => $this->transform($facility));

        return response()->json($facilities);
    }

    /**
     * GET /api/facilities/{id}
     * Single facility detail.
     */
    public function show($id)
    {
        $facility = Facility::with('city')->findOrFail($id);

        return response()->json($this->transform($facility));
    }

    private function transform(Facility $facility): array
    {
        return [
            'id'                => $facility->id,
            'facility_name'     => $facility->facility_name,
            'city'              => $facility->city->name ?? null,
            'area'              => $facility->area,
            'address'           => $facility->full_address,
            'latitude'          => (float) $facility->latitude,
            'longitude'         => (float) $facility->longitude,
            'accepted_items'    => $this->splitList($facility->accepted_ewaste),
            'pickup_available'  => ! empty($facility->pickup_availability),
            'working_hours'     => $facility->working_hours,
        ];
    }

    /**
     * "Mobile, Laptop, Battery" → ["Mobile", "Laptop", "Battery"]
     */
    private function splitList(?string $value): array
    {
        if (empty($value)) {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', $value))));
    }
}