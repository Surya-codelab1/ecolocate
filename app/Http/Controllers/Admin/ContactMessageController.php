<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::latest()->paginate(10);
        return view('admin.messages.index', compact('messages'));
    }

    public function updateStatus(Request $request, $id)
    {
        ContactMessage::findOrFail($id)->update(['status' => $request->status]);
        return back()->with('success', 'Message status updated.');
    }
}