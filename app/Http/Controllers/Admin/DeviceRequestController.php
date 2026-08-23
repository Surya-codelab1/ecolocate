<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeviceRequest;

class DeviceRequestController extends Controller
{
    public function index()
    {
        $requests = DeviceRequest::latest()->paginate(10);
        return view('admin.device-requests.index', compact('requests'));
    }

    public function approve($id)
    {
        DeviceRequest::findOrFail($id)->update(['status' => 'approved']);
        return back()->with('success', 'Device request approved.');
    }

    public function reject($id)
    {
        DeviceRequest::findOrFail($id)->update(['status' => 'rejected']);
        return back()->with('success', 'Device request rejected.');
    }
}