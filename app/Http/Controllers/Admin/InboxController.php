<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use App\Models\Subscriber;
use App\Models\TipSubmission;

class InboxController extends Controller
{
    public function index()
    {
        return view('admin.inbox', [
            'subscribers' => Subscriber::latest()->limit(100)->get(), 'subscriberCount' => Subscriber::count(),
            'contacts' => ContactSubmission::latest()->limit(50)->get(), 'tips' => TipSubmission::latest()->limit(50)->get(),
        ]);
    }
}
