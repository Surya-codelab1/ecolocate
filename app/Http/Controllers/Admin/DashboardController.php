<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Facility;
use App\Models\FacilityRequest;
use App\Models\Device;
use App\Models\DeviceRequest;
use App\Models\PickupRequest;
use App\Models\EcoCreditTransaction;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'         => User::where('role', 'user')->count(),
            'total_facilities'    => Facility::where('status', 'approved')->count(),
            'pending_facilities'  => FacilityRequest::where('status', 'pending')->count(),
            'total_devices'       => Device::count(),
            'pending_device_reqs' => DeviceRequest::where('status', 'pending')->count(),
            'total_pickups'       => PickupRequest::count(),
            'completed_pickups'   => PickupRequest::where('status', 'Completed')->count(),
            'ecocredits_issued'   => EcoCreditTransaction::sum('credits'),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}