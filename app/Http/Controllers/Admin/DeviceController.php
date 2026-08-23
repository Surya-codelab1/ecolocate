<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index()
    {
        $devices = Device::latest()->paginate(10);
        return view('admin.devices.index', compact('devices'));
    }

    public function create()
    {
        return view('admin.devices.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'brand' => 'required|string',
            'model_name' => 'required|string',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'materials' => 'nullable|string',
            'harmful_components' => 'nullable|string',
            'estimated_recycling_value' => 'nullable|numeric',
            'eco_credits' => 'required|integer',
            'recycling_information' => 'nullable|string',
        ]);

        Device::create($data);

        return redirect()->route('admin.devices.index')->with('success', 'Device added successfully!');
    }

    public function edit($id)
    {
        $device = Device::findOrFail($id);
        return view('admin.devices.edit', compact('device'));
    }

    public function update(Request $request, $id)
    {
        $device = Device::findOrFail($id);
        $data = $request->validate([
            'brand' => 'required|string',
            'model_name' => 'required|string',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'materials' => 'nullable|string',
            'harmful_components' => 'nullable|string',
            'estimated_recycling_value' => 'nullable|numeric',
            'eco_credits' => 'required|integer',
            'recycling_information' => 'nullable|string',
        ]);

        $device->update($data);

        return redirect()->route('admin.devices.index')->with('success', 'Device updated successfully!');
    }

    public function destroy($id)
    {
        Device::findOrFail($id)->delete();
        return back()->with('success', 'Device deleted.');
    }
}