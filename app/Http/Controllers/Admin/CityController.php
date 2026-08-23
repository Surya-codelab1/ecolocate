<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index()
    {
        $cities = City::latest()->paginate(10);
        return view('admin.cities.index', compact('cities'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'state' => 'required|string',
        ]);
        City::create($data);
        return back()->with('success', 'City added successfully!');
    }

    public function update(Request $request, $id)
    {
        $city = City::findOrFail($id);
        $city->update($request->only(['name', 'state', 'is_active']));
        return back()->with('success', 'City updated.');
    }

    public function destroy($id)
    {
        City::findOrFail($id)->delete();
        return back()->with('success', 'City removed.');
    }
}