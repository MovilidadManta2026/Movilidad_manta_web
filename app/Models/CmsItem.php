<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsItem extends Model
{
    protected $fillable = [
        'module',
        'key',
        'title',
        'content',
        'metadata',
        'status',
        'sort_order',
        'published_at',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'published_at' => 'datetime',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function metadataValue(string $key, ?string $default = null): ?string
    {
        $value = $this->metadata[$key] ?? $default;

        return filled($value) ? (string) $value : $default;
    }

    public function assetUrl(?string $fallback = null): ?string
    {
        $asset = $this->metadataValue('asset');

        if (! $asset) {
            return $fallback;
        }

        if (str_starts_with($asset, 'http') || str_starts_with($asset, '/')) {
            return $asset;
        }

        return '/storage/'.$asset;
    }

    public function actionUrl(): string
    {
        return $this->metadataValue('url') ?: route('public.content.show', $this);
    }

    public function displayDate(): string
    {
        $date = $this->metadataValue('date');

        return $date ?: ($this->published_at?->translatedFormat('d F, Y') ?? $this->created_at->translatedFormat('d F, Y'));
    }
}
