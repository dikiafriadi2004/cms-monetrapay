<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdRequest;
use App\Models\Ad;
use Illuminate\Support\Facades\Storage;

class AdController extends Controller
{
    /**
     * Display a listing of ads.
     */
    public function index()
    {
        $ads = Ad::with('user')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.ads.index', compact('ads'));
    }

    /**
     * Show the form for creating a new ad.
     */
    public function create()
    {
        return view('admin.ads.create');
    }

    /**
     * Store a newly created ad.
     */
    public function store(AdRequest $request)
    {
        $validated = $request->validated();

        // Handle image upload for manual type
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('ads', 'public');
        }

        Ad::create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'type' => $validated['type'],
            'code' => $validated['code'] ?? null,
            'image' => $imagePath,
            'position' => $validated['position'],
            'paragraph_number' => $validated['paragraph_number'] ?? null,
            'interval' => $validated['interval'] ?? null,
            'is_active' => $validated['is_active'] ?? false,
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
        ]);

        return redirect()->route('admin.ads.index')
            ->with('success', 'Slot iklan berhasil dibuat.');
    }

    /**
     * Show an ad (redirects to edit).
     */
    public function show(Ad $ad)
    {
        return redirect()->route('admin.ads.edit', $ad);
    }

    /**
     * Show the form for editing the specified ad.
     */
    public function edit(Ad $ad)
    {
        return view('admin.ads.edit', compact('ad'));
    }

    /**
     * Update the specified ad.
     */
    public function update(AdRequest $request, Ad $ad)
    {
        $validated = $request->validated();

        // Handle image upload for manual type
        $imagePath = $ad->image;
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($ad->image) {
                Storage::disk('public')->delete($ad->image);
            }
            $imagePath = $request->file('image')->store('ads', 'public');
        } elseif ($request->boolean('remove_image')) {
            if ($ad->image) {
                Storage::disk('public')->delete($ad->image);
            }
            $imagePath = null;
        }

        $ad->update([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'code' => $validated['code'] ?? null,
            'image' => $imagePath,
            'position' => $validated['position'],
            'paragraph_number' => $validated['paragraph_number'] ?? null,
            'interval' => $validated['interval'] ?? null,
            'is_active' => $validated['is_active'] ?? false,
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
        ]);

        return redirect()->route('admin.ads.index')
            ->with('success', 'Slot iklan berhasil diperbarui.');
    }

    /**
     * Remove the specified ad.
     */
    public function destroy(Ad $ad)
    {
        // Delete image if exists
        if ($ad->image) {
            Storage::disk('public')->delete($ad->image);
        }

        $ad->delete();

        return redirect()->route('admin.ads.index')
            ->with('success', 'Slot iklan berhasil dihapus.');
    }

    /**
     * Toggle the active status of an ad.
     */
    public function toggleStatus(Ad $ad)
    {
        // If activating, check max 5 active ads per position
        if (! $ad->is_active) {
            $activeCount = Ad::where('position', $ad->position)
                ->where('is_active', true)
                ->where('id', '!=', $ad->id)
                ->count();

            if ($activeCount >= 5) {
                return redirect()->route('admin.ads.index')
                    ->with('error', "Maksimal 5 slot iklan aktif per posisi '{$ad->position}'. Nonaktifkan slot lain terlebih dahulu.");
            }
        }

        $ad->update(['is_active' => ! $ad->is_active]);

        $status = $ad->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.ads.index')
            ->with('success', "Slot iklan berhasil {$status}.");
    }
}
