<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Show the public contact page.
     */
    public function index()
    {
        return view('public.contact-us');
    }

    /**
     * Store a new message so it shows up in admin > Messages.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:120',
            'email'   => 'required|email|max:150',
            'phone'   => 'nullable|string|max:20',
            'subject' => 'required|string|max:150',
            'message' => 'required|string|max:2000',
        ]);

        // NOTE: adjust the column names/default status below to match your
        // actual `contact_messages` migration if they differ.
        ContactMessage::create($data + ['status' => 'unread']);

        return back()->with('success', 'Thanks for reaching out! Our team will get back to you soon.');
    }
}