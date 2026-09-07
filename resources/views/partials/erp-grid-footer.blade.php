@php
    $perPageOptions = $perPageOptions ?? [10, 25, 50, 100];
    $currentPerPage = (int) request('per_page', $paginator->perPage());
    $currentPage = $paginator->currentPage();
    $lastPage = max(1, $paginator->lastPage());
    $from = $paginator->firstItem() ?? 0;
    $to = $paginator->lastItem() ?? 0;
    $total = $paginator->total();
    $gridQuery = request()->except(['page', 'per_page']);
@endphp

<div
    class="erp-grid-footer"
    data-base-url="{{ url()->current() }}"
    data-query='@json($gridQuery)'
    data-current-page="{{ $currentPage }}"
    data-last-page="{{ $lastPage }}"
>
    <div class="erp-grid-footer-left">
        <button type="button" class="erp-grid-icon-btn" title="Search" data-erp-grid-search>
            <i class="fa fa-search"></i>
        </button>
        <label class="erp-grid-per-page-label">
            Show
            <select class="erp-grid-select" data-erp-grid-per-page>
                @foreach ($perPageOptions as $option)
                    <option value="{{ $option }}" @selected($currentPerPage === $option)>{{ $option }}</option>
                @endforeach
            </select>
            entries
        </label>
    </div>

    <div class="erp-grid-footer-center">
        <button type="button" class="erp-grid-nav-btn" title="First page" data-erp-grid-page="1" @disabled($currentPage <= 1)>
            <i class="fa fa-angle-double-left"></i>
        </button>
        <button type="button" class="erp-grid-nav-btn" title="Previous page" data-erp-grid-page="{{ max(1, $currentPage - 1) }}" @disabled($currentPage <= 1)>
            <i class="fa fa-angle-left"></i>
        </button>

        <span class="erp-grid-separator"></span>

        <span class="erp-grid-page-label">
            Page
            <input
                type="text"
                class="erp-grid-page-input"
                value="{{ $currentPage }}"
                data-erp-grid-page-input
                aria-label="Current page"
            >
            of {{ $lastPage }}
        </span>

        <span class="erp-grid-separator"></span>

        <button type="button" class="erp-grid-nav-btn" title="Next page" data-erp-grid-page="{{ min($lastPage, $currentPage + 1) }}" @disabled($currentPage >= $lastPage)>
            <i class="fa fa-angle-right"></i>
        </button>
        <button type="button" class="erp-grid-nav-btn" title="Last page" data-erp-grid-page="{{ $lastPage }}" @disabled($currentPage >= $lastPage)>
            <i class="fa fa-angle-double-right"></i>
        </button>

        <button type="button" class="erp-grid-refresh-btn" title="Refresh" data-erp-grid-refresh>
            <i class="fa fa-refresh"></i>
        </button>
    </div>

    <div class="erp-grid-footer-right">
        Displaying {{ $from }} to {{ $to }} of {{ $total }} items
    </div>
</div>
