<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class MenuItem extends Model
{
    protected $guarded = [];

    protected $casts = [
        'parent_id' => 'integer',
        'page_id' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public const LOCATIONS = ['header', 'footer_care'];

    protected static function booted(): void
    {
        static::deleting(function (MenuItem $item) {
            foreach ($item->children()->get() as $child) {
                if ((int) $child->id !== (int) $item->id) {
                    $child->delete();
                }
            }
        });
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id');
    }

    public function resolveHref(): string
    {
        if ($this->type === 'page') {
            if ($this->page) {
                return url('/' . ltrim((string) $this->page->slug, '/'));
            }
            return '#';
        }

        $raw = trim((string) ($this->url ?? ''));
        if ($raw === '' || $raw === '#') {
            return '#';
        }

        if (preg_match('/^(https?:\/\/|mailto:|tel:)/i', $raw) || str_starts_with($raw, '#')) {
            return $raw;
        }

        $fragment = '';
        $pathAndQuery = $raw;
        if (str_contains($raw, '#')) {
            [$pathAndQuery, $fragPart] = explode('#', $raw, 2);
            $fragment = '#' . $fragPart;
        }

        $normalized = '/' . ltrim($pathAndQuery, '/');
        if ($normalized === '/') {
            return url('/') . $fragment;
        }

        return url($normalized) . $fragment;
    }

    public static function isHrefActive(string $resolvedHref): bool
    {
        if ($resolvedHref === '#' || str_starts_with($resolvedHref, '#') || str_contains($resolvedHref, '#')) {
            return false;
        }

        $request = request();
        $targetParts = parse_url($resolvedHref);
        if (!$targetParts) {
            return false;
        }

        $targetHost = strtolower($targetParts['host'] ?? '');
        $currentHost = strtolower($request->getHost());
        if ($targetHost !== '' && $currentHost !== '' && $targetHost !== $currentHost) {
            return false;
        }

        $targetPath = '/' . trim($targetParts['path'] ?? '/', '/');
        if ($targetPath === '') {
            $targetPath = '/';
        }

        $currentPath = '/' . trim($request->getPathInfo(), '/');
        if ($currentPath === '') {
            $currentPath = '/';
        }

        if ($targetPath !== $currentPath) {
            return false;
        }

        $targetQuery = $targetParts['query'] ?? '';
        if ($targetQuery !== '') {
            parse_str($targetQuery, $targetQueryParams);
            foreach ($targetQueryParams as $k => $v) {
                if ((string) $request->query($k) !== (string) $v) {
                    return false;
                }
            }
            return true;
        }

        return count($request->query()) === 0;
    }

    /**
     * Build nested menu tree for a storefront or admin location.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function buildTree(string $location, bool $onlyActive = true): array
    {
        $query = self::with('page')
            ->where('location', $location)
            ->orderBy('sort_order')
            ->orderBy('id');

        if ($onlyActive) {
            $query->where('is_active', true);
        }

        $items = $query->get();
        if ($items->isEmpty()) {
            return [];
        }

        return self::assembleBranch($items, null, $onlyActive, 0, []);
    }

    /**
     * @param Collection<int, MenuItem> $items
     * @param array<int, bool> $visited
     * @return array<int, array<string, mixed>>
     */
    protected static function assembleBranch(Collection $items, ?int $parentId, bool $onlyActive, int $depth, array $visited): array
    {
        if ($depth > 10) {
            return [];
        }

        $branch = [];
        $siblings = $items->filter(function (MenuItem $item) use ($parentId) {
            if ($parentId === null) {
                return $item->parent_id === null || (int) $item->parent_id === 0;
            }
            return (int) $item->parent_id === $parentId;
        });

        foreach ($siblings as $item) {
            $id = (int) $item->id;
            if (isset($visited[$id])) {
                continue;
            }

            $nextVisited = $visited;
            $nextVisited[$id] = true;

            $children = self::assembleBranch($items, $id, $onlyActive, $depth + 1, $nextVisited);

            $isUnpublishedPage = $onlyActive
                && $item->type === 'page'
                && $item->page !== null
                && !$item->page->is_published;

            if ($isUnpublishedPage && empty($children)) {
                continue;
            }

            $href = $isUnpublishedPage ? '#' : $item->resolveHref();

            $branch[] = [
                'id' => $id,
                'parent_id' => $item->parent_id ? (int) $item->parent_id : null,
                'location' => $item->location,
                'title' => $item->title,
                'type' => $item->type,
                'page_id' => $item->page_id ? (int) $item->page_id : null,
                'page_title' => $item->page?->title,
                'page_slug' => $item->page?->slug,
                'page_published' => $item->page ? (bool) $item->page->is_published : null,
                'url' => $item->url,
                'resolved_url' => $href,
                'badge_text' => $item->badge_text,
                'target' => $item->target === '_blank' ? '_blank' : '_self',
                'sort_order' => (int) $item->sort_order,
                'is_active' => (bool) $item->is_active,
                'is_current' => $onlyActive ? self::isHrefActive($href) : false,
                'depth' => $depth,
                'children' => $children,
            ];
        }

        return $branch;
    }

    public static function wouldCreateCycle(?int $itemId, ?int $candidateParentId): bool
    {
        if (!$itemId || !$candidateParentId) {
            return false;
        }
        if ($itemId === $candidateParentId) {
            return true;
        }

        $parents = self::pluck('parent_id', 'id')->all();
        $cursor = $candidateParentId;
        $steps = 0;

        while ($cursor !== null && $steps < 50) {
            if ((int) $cursor === $itemId) {
                return true;
            }
            $next = $parents[$cursor] ?? null;
            $cursor = $next !== null ? (int) $next : null;
            $steps++;
        }

        return false;
    }
}
