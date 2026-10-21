<?php

namespace App\Http\Controllers;

use App\Models\GalleryImage;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

class GalleryController extends Controller
{
    /**
     * Show the published gallery images grouped by album.
     */
    public function index(): View
    {
        $images = GalleryImage::query()
            ->published()
            ->select(['id', 'title', 'caption', 'path', 'album', 'order'])
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        $albums = $images
            ->groupBy(fn (GalleryImage $image): string => $image->album ?? 'عمومی')
            ->map(fn (Collection $items): Collection => $items->values());

        return view('pages.gallery', [
            'albums' => $albums,
            'images' => $images,
        ]);
    }
}
