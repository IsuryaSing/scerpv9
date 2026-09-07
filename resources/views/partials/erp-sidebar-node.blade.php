@props(['items' => [], 'level' => 0])

<ul class="erp-menu-list {{ $level > 0 ? 'erp-submenu' : '' }}">
    @foreach ($items as $item)
        <li class="erp-menu-item {{ !empty($item['has_children']) ? 'has-children' : '' }} {{ !empty($item['has_children']) && $level < 1 ? 'is-open' : '' }}">
            <div class="erp-menu-row">
                <a
                    href="{{ $item['url'] }}"
                    class="erp-menu-link {{ request()->url() === $item['url'] ? 'is-current' : '' }}"
                >
                    <i class="fa {{ $item['icon'] }}"></i>
                    <span>{{ $item['label'] }}</span>
                </a>

                @if (!empty($item['has_children']))
                    <button
                        type="button"
                        class="erp-menu-toggle"
                        aria-expanded="{{ !empty($item['has_children']) && $level < 1 ? 'true' : 'false' }}"
                        aria-label="Toggle {{ $item['label'] }}"
                    >
                        <i class="fa fa-angle-right"></i>
                    </button>
                @endif
            </div>

            @if (!empty($item['children']))
                @include('partials.erp-sidebar-node', ['items' => $item['children'], 'level' => $level + 1])
            @endif
        </li>
    @endforeach
</ul>
