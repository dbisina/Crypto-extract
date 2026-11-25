<?php

namespace App\Http\Controllers;

use App\Models\ContactSubmission;
use App\Models\Subscriber;
use App\Models\TipSubmission;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function newsletter(Request $r)
    {
        $data = $r->validate(['email' => 'required|email:rfc|max:190', 'source' => 'nullable|string|max:40']);
        Subscriber::firstOrCreate(['email' => strtolower($data['email'])], ['source' => $data['source'] ?? 'site']);

        $msg = 'You\'re on the list. Look out for The Daily Extract.';
        return $r->wantsJson() ? response()->json(['message' => $msg]) : back()->with('status', $msg);
    }

    public function contact(Request $r)
    {
        $data = $r->validate([
            'name' => 'required|string|max:120', 'email' => 'required|email|max:190',
            'topic' => 'nullable|string|max:60', 'message' => 'required|string|min:10|max:4000',
        ]);
        ContactSubmission::create($data);
        return back()->with('status', 'Thank you. Your message has reached the newsroom.');
    }

    public function tip(Request $r)
    {
        $data = $r->validate([
            'name' => 'nullable|string|max:120', 'email' => 'nullable|email|max:190',
            'subject' => 'required|string|max:160', 'country' => 'nullable|string|max:60',
            'details' => 'required|string|min:20|max:6000',
        ]);
        TipSubmission::create($data);
        return back()->with('status', 'Tip received. Thank you; our editors will review it.');
    }
}
