<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsItem;
use App\Models\MediaAsset;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $allowedModules = collect(\App\Http\Controllers\Admin\CmsItemController::MODULES)
            ->keys()
            ->filter(fn (string $module) => request()->user()->canManageModule($module));

        return view('admin.dashboard', [
            'counts' => [
                'items' => CmsItem::whereIn('module', $allowedModules)->count(),
                'published' => CmsItem::whereIn('module', $allowedModules)->where('status', 'published')->count(),
                'media' => MediaAsset::whereIn('module', $allowedModules)->count(),
                'users' => User::count(),
            ],
            'recentItems' => CmsItem::whereIn('module', $allowedModules)->latest()->take(8)->get(),
            'modules' => CmsItem::query()
                ->selectRaw('module, count(*) as total')
                ->whereIn('module', $allowedModules)
                ->groupBy('module')
                ->orderBy('module')
                ->get(),
        ]);
    }
}
