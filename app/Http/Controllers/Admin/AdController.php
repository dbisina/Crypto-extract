<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\Request;

class AdController extends Controller
{
    public function index()
    {
        return view('admin.ads.index', ['ads' => Advertisement::orderByDesc('active')->orderBy('placement')->get()]);
    }

    public function create() { return view('admin.ads.form', ['ad' => new Advertisement(['active' => true])]); }

    public function store(Request $r)
    {
        Advertisement::create($this->data($r));
        return redirect()->route('admin.ads.index')->with('status', 'Advertisement created.');
    }

    public function edit(Advertisement $advertisement) { return view('admin.ads.form', ['ad' => $advertisement]); }

    public function update(Request $r, Advertisement $advertisement)
    {
        $advertisement->update($this->data($r));
        return redirect()->route('admin.ads.index')->with('status', 'Advertisement updated.');
    }

    public function destroy(Advertisement $advertisement)
    {
        $advertisement->delete();
        return redirect()->route('admin.ads.index')->with('status', 'Advertisement deleted.');
    }

    private function data(Request $r): array
    {
        $d = $r->validate([
            'name' => 'required|string|max:120', 'advertiser' => 'required|string|max:120',
            'placement' => 'required|in:'.implode(',', array_keys(Advertisement::PLACEMENTS)),
            'headline' => 'nullable|string|max:160', 'url' => 'required|url|max:500',
            'starts_at' => 'nullable|date', 'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'banner_file' => 'nullable|image|max:4096',
        ]);
        $d['active'] = $r->boolean('active');
        if ($r->hasFile('banner_file')) $d['banner'] = $r->file('banner_file')->store('uploads/ads', 'public');
        unset($d['banner_file']);
        return $d;
    }
}
