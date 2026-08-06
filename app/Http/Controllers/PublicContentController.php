<?php

namespace App\Http\Controllers;

use App\Models\CmsItem;
use Illuminate\View\View;

class PublicContentController extends Controller
{
    public function show(CmsItem $cmsItem): View
    {
        abort_unless($cmsItem->status === 'published', 404);

        return view('public.content-show', [
            'item' => $cmsItem,
        ]);
    }
}
