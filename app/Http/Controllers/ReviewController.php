<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\ReviewImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Review::with([
                'customer:id,full_name',
                'product:id,name',
                'order:id,order_no'
            ]);

            return datatables()->eloquent($query)
                ->addIndexColumn()

                ->addColumn('product_name', fn ($r) =>
                    $r->product?->name ?? '-'
                )

                ->addColumn('customer_name', fn ($r) =>
                    $r->customer?->full_name ?? '-'
                )

                ->addColumn('order_no', fn ($r) =>
                    $r->order?->order_no ?? '-'
                )

                ->addColumn('rating', fn ($r) =>
                    '<span class="badge '.$r->ratingBadgeClass().'">'
                    .$r->rating.' ★</span>'
                )

                ->addColumn('status', fn ($r) =>
                    '<span class="badge bg-secondary">'
                    .ucfirst($r->status).'</span>'
                )

                ->addColumn('created_at', fn ($r) =>
                $r->created_at?->format('d M Y H:i')
                )

                ->addColumn('action', fn ($r) =>
                view('reviews._column_action', compact('r'))->render()
                )

                ->rawColumns(['rating','status','action'])
                ->toJson();
        }

        return view('reviews.index');
    }

    public function show(int $id)
    {
        $review = Review::with(['customer','product','images'])->findOrFail($id);
        return view('reviews.show', compact('review'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|in:published,pending,hidden',
        ]);

        Review::findOrFail($id)->update([
            'status' => $request->status,
        ]);

        return back()->with('success','Status review diperbarui');
    }

    public function destroy(int $id)
    {
        Review::findOrFail($id)->delete();
        return back()->with('success','Review dihapus');
    }

    public function destroyImage(int $id)
    {
        $image = ReviewImage::findOrFail($id);

        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return back()->with('success','Gambar review dihapus');
    }
}
