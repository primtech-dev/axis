<?php

namespace App\Http\Controllers;

use App\Models\HomeBanner;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Support\ImageUploader;
use Illuminate\Support\Facades\Storage;

class HomeBannerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = HomeBanner::query();

            return datatables()->eloquent($query)
                ->addIndexColumn()
                ->addColumn('image', function ($b) {
                    return '<img src="'.$b->image_url.'"
                 style="max-width:120px;height:auto"
                 class="rounded img-fluid">';
                })

                ->addColumn('status', fn ($b) =>
                $b->is_active ? 'Aktif' : 'Non-aktif'
                )
                ->addColumn('created_at', fn ($b) =>
                $b->created_at?->format('d M Y H:i')
                )
                ->addColumn('action', fn ($b) =>
                view('home-banners._column_action', compact('b'))->render()
                )
                ->rawColumns(['image','action'])
                ->toJson();
        }

        return view('home-banners.index');
    }

    public function create()
    {
        return view('home-banners.create', [
            'banner' => new HomeBanner()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:100|unique:home_banners,code',
            'title' => 'nullable|string|max:255',
            'link_url' => 'nullable|url',
            'sort_order' => 'nullable|integer',
            'is_active' => 'sometimes|boolean',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'image' => 'required|image|max:5120',
            'image_mobile' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = ImageUploader::uploadWebp(
                $request->file('image'),
                'home-banners',
                1920,
                80
            );
        }

        if ($request->hasFile('image_mobile')) {
            $validated['image_mobile_path'] = ImageUploader::uploadWebp(
                $request->file('image_mobile'),
                'home-banners/mobile',
                900,
                80
            );
        }

        $validated['created_by'] = auth()->id();

        HomeBanner::create($validated);

        return redirect()
            ->route('admin.home-banners.index')
            ->with('success', 'Home banner berhasil ditambahkan');
    }

    public function edit(int $id)
    {
        $banner = HomeBanner::findOrFail($id);
        return view('home-banners.edit', compact('banner'));
    }

    public function update(Request $request, int $id)
    {
        $banner = HomeBanner::findOrFail($id);

        $validated = $request->validate([
            'code' => ['required','string', Rule::unique('home_banners','code')->ignore($banner->id)],
            'title' => 'nullable|string|max:255',
            'link_url' => 'nullable|url',
            'sort_order' => 'nullable|integer',
            'is_active' => 'sometimes|boolean',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'image' => 'nullable|image|max:5120',
            'image_mobile' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($banner->image_path);
            $validated['image_path'] = ImageUploader::uploadWebp(
                $request->file('image'),
                'home-banners',
                1920,
                80
            );
        }

        if ($request->hasFile('image_mobile')) {
            Storage::disk('public')->delete($banner->image_mobile_path);
            $validated['image_mobile_path'] = ImageUploader::uploadWebp(
                $request->file('image_mobile'),
                'home-banners/mobile',
                900,
                80
            );
        }

        $banner->update($validated);

        return redirect()
            ->route('admin.home-banners.index')
            ->with('success', 'Home banner berhasil diperbarui');
    }

    public function destroy(int $id)
    {
        HomeBanner::findOrFail($id)->delete();
        return back()->with('success', 'Home banner berhasil dihapus');
    }
}
