<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PickupRequest;

class PickupController extends Controller
{
    public function index()
    {
        $pickups = PickupRequest::with(['user', 'facility', 'device'])->latest()->paginate(15);
        return view('admin.pickups.index', compact('pickups'));
    }
}