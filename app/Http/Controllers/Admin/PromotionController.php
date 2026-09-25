<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        return view('admin.promotions.index', ['promotions' => Promotion::latest()->paginate(20)]);
    }

    public function create()
    {
        return view('admin.promotions.form', ['promotion' => new Promotion]);
    }

    public function store(Request $request)
    {
        Promotion::create($this->validated($request));

        return redirect()->route('admin.promotions.index')->with('status', 'Promotion created.');
    }

    public function edit(Promotion $promotion)
    {
        return view('admin.promotions.form', compact('promotion'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $promotion->update($this->validated($request));

        return redirect()->route('admin.promotions.index')->with('status', 'Promotion updated.');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();

        return back()->with('status', 'Promotion deleted.');
    }

    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'description' => ['nullable', 'string', 'max:500'],
            'link' => ['nullable', 'string', 'max:255', 'regex:#^(https?://|/)#i'],
            'placement' => ['required', 'in:homepage,packages,popup'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('promotions', 'public');
        } else {
            unset($data['image']);
        }

        return $data;
    }
}
