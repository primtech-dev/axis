<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Support\ImageUploader;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    private const VALIDATION_MESSAGES = [
        'name.required' => 'Nama kategori tidak boleh kosong',
        'slug.unique'   => 'Slug sudah digunakan, gunakan nama lain atau tambahkan suffix',
        'parent_id.exists' => 'Kategori parent tidak ditemukan',
    ];

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Category::select(['id','name','slug','parent_id','position','is_active','created_at', 'thumbnail'])
                ->with('parent');

            $searchValue = $request->input('search.value');
            if (!empty($searchValue)) {
                $query->where(function ($q) use ($searchValue) {
                    $q->where('name', 'ilike', "%{$searchValue}%")
                        ->orWhere('slug', 'ilike', "%{$searchValue}%");
                });
            }

            return datatables()->eloquent($query)
                ->addIndexColumn()
                ->addColumn('parent', function (Category $c) {
                    return $c->parent ? e($c->parent->name) : '-';
                })
                ->addColumn('is_active', function (Category $c) {
                    return $c->is_active ? 'Aktif' : 'Non-aktif';
                })
                ->addColumn('created_at', function ($c) {
                    return $c->created_at ? $c->created_at->format('d M Y H:i') : '-';
                })
                ->addColumn('thumbnail', function (Category $c) {
                    return $c->thumbnail_url
                        ? '<img src="'.$c->thumbnail_url.'" width="40" class="rounded">'
                        : '-';
                })
                ->addColumn('action', function ($c) {
                    return view('categories._column_action', ['c' => $c])->render();
                })
                ->rawColumns(['thumbnail','action'])
                ->toJson();
        }

        return view('categories.index');
    }

    public function create()
    {
        $parents = Category::whereNull('parent_id')->orderBy('position')->get();
        return view('categories.create', ['category' => new Category(), 'parents' => $parents]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'parent_id' => 'nullable|integer|exists:categories,id',
            'position' => 'nullable|integer',
            'is_active' => 'sometimes|boolean',
            'banner' => 'nullable|image|max:5120',
            'banner_alt' => 'nullable|string|max:255',
            'thumbnail' => 'nullable|image|max:3000',
        ], self::VALIDATION_MESSAGES);

        try {
            if ($request->hasFile('banner')) {
                $validated['banner_path'] = ImageUploader::uploadWebp(
                    $request->file('banner'),
                    'categories/banners',
                    1600,
                    80
                );
            }

            if ($request->hasFile('thumbnail')) {
                $validated['thumbnail'] = ImageUploader::uploadWebp(
                    $request->file('thumbnail'),
                    'categories/thumbnails',
                    600,   // thumbnail lebih kecil
                    80
                );
            }

            Category::create($validated);

            return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function edit(int $id)
    {
        $category = Category::find($id);
        if (!$category) return abort(404);
        $parents = Category::where('id', '<>', $id)->whereNull('parent_id')->orderBy('position')->get();
        return view('categories.edit', compact('category','parents'));
    }

    public function update(Request $request, int $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['nullable','string','max:255', Rule::unique('categories','slug')->ignore($category->id)],
            'parent_id' => ['nullable','integer','exists:categories,id', function($attribute, $value, $fail) use ($category) {
                if ($value && $value == $category->id) $fail('Parent category tidak boleh sama dengan kategori itu sendiri.');
            }],
            'position' => 'nullable|integer',
            'is_active' => 'sometimes|boolean',
            'banner' => 'nullable|image|max:5120',
            'banner_alt' => 'nullable|string|max:255',
            'thumbnail' => 'nullable|image|max:3000',
        ], self::VALIDATION_MESSAGES);

        try {
            if ($request->hasFile('banner')) {
                // hapus banner lama
                if ($category->banner_path) {
                    Storage::disk('public')->delete($category->banner_path);
                }

                $validated['banner_path'] = ImageUploader::uploadWebp(
                    $request->file('banner'),
                    'categories/banners',
                    1600,
                    80
                );
            }

            if ($request->hasFile('thumbnail')) {
                if ($category->thumbnail) {
                    Storage::disk('public')->delete($category->thumbnail);
                }

                $validated['thumbnail'] = ImageUploader::uploadWebp(
                    $request->file('thumbnail'),
                    'categories/thumbnails',
                    600,
                    80
                );
            }


            $category->update($validated);
            return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function destroy(int $id)
    {
        $category = Category::findOrFail($id);
        try {
            $category->delete();
            return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function show(int $id)
    {
        $category = Category::with('parent','children')->findOrFail($id);
        return view('categories.show', compact('category'));
    }
}
