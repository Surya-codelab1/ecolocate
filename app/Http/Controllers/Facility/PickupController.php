<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\PickupRequest;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PickupController extends Controller
{
    public function index()
    {
        $facility = Facility::where('user_id', auth()->id())->firstOrFail();

        $pickups = $facility->pickupRequests()->latest()->paginate(10);

        return view('facility.pickups.index', compact('pickups', 'facility'));
    }

    public function updateStatus(Request $request, $id)
    {
        $facility = Facility::where('user_id', auth()->id())->firstOrFail();

        $pickup = $facility->pickupRequests()->findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:Accepted,Scheduled,Collected,Completed,Cancelled',
        ]);

        $pickup->update([
            'status'       => $validated['status'],
            'completed_at' => $validated['status'] === 'Completed' ? now() : $pickup->completed_at,
        ]);

        return back()->with('success', 'Pickup status updated.');
    }

    public function generateCertificate($id)
    {
        $facility = Facility::where('user_id', auth()->id())->firstOrFail();

        $pickup = $facility->pickupRequests()
            ->with(['user', 'device'])
            ->where('status', 'Completed')
            ->findOrFail($id);

        $pdf = Pdf::loadView('pickup-requests.certificate', compact('pickup', 'facility'));

        $fileName = 'certificates/certificate-' . $pickup->id . '-' . time() . '.pdf';

        Storage::disk('public')->put($fileName, $pdf->output());

        $pickup->update(['certificate_path' => $fileName]);

        return back()->with('success', 'Certificate generated successfully.');
    }
}