<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class AwarenessController extends Controller
{
    public function index()
    {
        return view('public.awareness');
    }
}