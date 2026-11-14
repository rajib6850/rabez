<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Page extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_published' => 'boolean',
        'sort_order' => 'integer',
    ];

    public const RESERVED_SLUGS = [
        'shop',
        'checkout',
        'admin',
        'track-order',
        'product',
        'login',
        'register',
        'logout',
        'api',
        'assets',
        'downloads',
        'storage',
        'up',
        'page',
        'sw.js',
        'manifest.json',
        'admin-manifest.json',
        'favicon.ico',
        'robots.txt',
        'sitemap.xml',
    ];

    public function menuItems(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'page_id');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function getPublicUrlAttribute(): string
    {
        return url('/' . ltrim((string) $this->slug, '/'));
    }

    public static function isReservedSlug(string $slug): bool
    {
        $normalized = strtolower(trim($slug, '/ '));
        return in_array($normalized, self::RESERVED_SLUGS, true);
    }

    public static function makeUniqueSlug(string $source, ?int $ignoreId = null): string
    {
        $base = Str::slug($source);
        if ($base === '') {
            $base = 'page';
        }
        if (self::isReservedSlug($base)) {
            $base .= '-page';
        }

        $slug = $base;
        $counter = 2;

        while (
            self::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
            || self::isReservedSlug($slug)
        ) {
            $slug = $base . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Sanitize rich HTML authored in Admin CMS before storage/display.
     */
    public static function sanitizeHtml(?string $html): string
    {
        $clean = trim((string) ($html ?? ''));
        if ($clean === '') {
            return '';
        }

        // Remove dangerous block tags and their inner contents
        $clean = preg_replace('#<(script|style|iframe|object|embed|form|meta|link|base)\b[^>]*>.*?</\1>#is', '', $clean) ?? $clean;
        // Remove standalone dangerous void tags
        $clean = preg_replace('#<(script|iframe|object|embed|meta|link|base)\b[^>]*\/?>#is', '', $clean) ?? $clean;
        // Remove inline event handlers (onclick, onload, onerror, etc.)
        $clean = preg_replace('/\s+on[a-z]+\s*=\s*(?:"[^"]*"|\'[^\']*\'|[^\s>]+)/is', '', $clean) ?? $clean;
        // Neutralize javascript:/vbscript:/data:text/html URLs inside href/src
        $clean = preg_replace('/(href|src)\s*=\s*(["\'])\s*(?:javascript|vbscript|data\s*:\s*text\/html):[^"\']*\2/is', '$1=$2#$2', $clean) ?? $clean;

        return trim($clean);
    }
}
