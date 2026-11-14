@php
  $level = isset($level) ? (int) $level : 0;
  $hasActiveDescendant = function (array $node) use (&$hasActiveDescendant): bool {
    if (!empty($node['is_current'])) {
      return true;
    }
    foreach ($node['children'] ?? [] as $child) {
      if ($hasActiveDescendant($child)) {
        return true;
      }
    }
    return false;
  };
@endphp

@foreach($items as $item)
  @php
    $hasChildren = !empty($item['children']);
    $isRoot = $level === 0;
    $isCurrent = !empty($item['is_current']);
    $inActiveBranch = !$isCurrent && $hasChildren && $hasActiveDescendant($item);
    $href = $item['resolved_url'] ?? '#';
    $targetBlank = ($item['target'] ?? '_self') === '_blank';
  @endphp

  <li class="rabez-nav-item {{ $isRoot ? 'rabez-nav-item--root' : 'rabez-nav-item--sub' }} {{ $hasChildren ? 'has-children' : '' }} {{ $inActiveBranch ? 'is-branch-active' : '' }}" data-nav-depth="{{ $level }}">
    <div class="rabez-nav-item__row">
      <a href="{{ $href }}"
         @if($targetBlank) target="_blank" rel="noopener noreferrer" @endif
         class="{{ $isRoot ? 'rabez-nav__link' : 'rabez-nav__sublink' }} {{ $isCurrent ? 'is-active' : '' }} {{ $hasChildren ? 'has-submenu' : '' }}"
         @if($href === '#') data-nav-placeholder="1" @endif>
        <span class="rabez-nav__label">{{ $item['title'] }}</span>
        @if(!empty($item['badge_text']))
          <span class="rabez-nav__tag">{{ $item['badge_text'] }}</span>
        @endif
        @if($hasChildren)
          <span class="rabez-nav__caret" aria-hidden="true">
            @if($isRoot)
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
            @else
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 6 15 12 9 18"></polyline></svg>
            @endif
          </span>
        @endif
      </a>

      @if($hasChildren)
        <button type="button"
                class="rabez-nav__submenu-toggle js-nav-submenu-toggle"
                aria-expanded="false"
                aria-label="Toggle {{ $item['title'] }} submenu">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
        </button>
      @endif
    </div>

    @if($hasChildren)
      <ul class="rabez-nav__dropdown rabez-nav__dropdown--depth-{{ min($level + 1, 4) }}">
        @include('partials.storefront-menu-items', ['items' => $item['children'], 'level' => $level + 1])
      </ul>
    @endif
  </li>
@endforeach
