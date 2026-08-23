<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;

class DeviceSearchController extends Controller
{
    /**
     * Show the public device search page.
     */
    public function index()
    {
        $categories = Device::whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        // A small initial set so the page isn't empty before the user searches.
        $initialDevices = Device::orderBy('brand')->limit(9)->get([
            'id', 'brand', 'model_name', 'category', 'description',
            'materials', 'harmful_components', 'estimated_recycling_value',
            'eco_credits', 'recycling_information',
        ]);

        return view('public.device-search', compact('categories', 'initialDevices'));
    }

    /**
     * AJAX endpoint: returns matching devices as JSON.
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:100',
        ]);

        $query = trim((string) $request->get('q', ''));
        $category = $request->get('category');

        $devices = Device::query()
            ->when($query !== '', function ($q) use ($query) {
                $q->where(function ($sub) use ($query) {
                    $sub->where('brand', 'like', "%{$query}%")
                        ->orWhere('model_name', 'like', "%{$query}%")
                        ->orWhere('category', 'like', "%{$query}%");
                });
            })
            ->when($category, fn ($q) => $q->where('category', $category))
            ->orderBy('brand')
            ->limit(60)
            ->get([
                'id', 'brand', 'model_name', 'category', 'description',
                'materials', 'harmful_components', 'estimated_recycling_value',
                'eco_credits', 'recycling_information',
            ]);

        return response()->json($devices);
    }
}