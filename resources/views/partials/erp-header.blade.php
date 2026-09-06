@php($header = $header ?? [])
<header class="erp-header">
    <div class="erp-header-inner">
        <div class="erp-col erp-col-left">
            <button type="button" class="erp-menu-btn" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>

            <a href="{{ route('selectmodule') }}" class="erp-brand">
                <img src="{{ asset('images/software_eng.png') }}" alt="Surya.erp" class="erp-brand-logo">
            </a>

            <div class="erp-school-name" title="{{ $header['schoolName'] ?? '' }}">
                {{ $header['schoolName'] ?? '' }}
                @if (!empty($header['schoolShortName']))
                    <span class="erp-school-short">({{ $header['schoolShortName'] }})</span>
                @endif
            </div>
        </div>

        <div class="erp-col erp-col-center">
            <div class="erp-search-wrap">
                <input
                    type="text"
                    id="erp-search-input"
                    class="erp-search-input"
                    placeholder="SEARCH"
                    autocomplete="off"
                >
                <span class="erp-search-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27A6.47 6.47 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                </span>

                <div class="ticode_list" id="erp-search-results" style="display: none;">
                    <ul class="search_tcode_data" id="erp-search-list"></ul>
                </div>
            </div>
        </div>

        <div class="erp-col erp-col-right">
            <button type="button" class="erp-notify-btn" aria-label="Notifications">
                <svg viewBox="0 0 24 24"><path d="M12 22a2.5 2.5 0 0 0 2.45-2h-4.9A2.5 2.5 0 0 0 12 22zm7-6V11a7 7 0 0 0-5-6.71V4a2 2 0 1 0-4 0v.29A7 7 0 0 0 5 11v5l-2 2v1h18v-1l-2-2z"/></svg>
                <span class="erp-notify-badge">0</span>
            </button>

            <div class="erp-chip">
                <label for="erp-unit-select">Unit Code:</label>
                <select
                    id="erp-unit-select"
                    class="erp-header-select erp-unit-select"
                    title="{{ $header['unitLabel'] ?? '' }}"
                >
                    @foreach ($header['availableUnits'] ?? [] as $unit)
                        <option value="{{ $unit['id'] }}" @selected(($header['unitCode'] ?? '') == $unit['id'])>
                            {{ $unit['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="erp-chip">
                <label for="erp-session-select">Session Year:</label>
                <select id="erp-session-select" class="erp-header-select">
                    @foreach ($header['sessionYears'] ?? [] as $year)
                        <option
                            value="{{ $year['id'] }}"
                            @selected(($header['sessionYearId'] ?? '') == $year['id'])
                        >
                            {{ $year['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="erp-user-item">
                <button type="button" class="erp-user-btn" id="erp-user-toggle">
                    {{ $header['userName'] ?? 'User' }}
                    <span class="erp-caret">▾</span>
                </button>
                <div class="erp-user-menu" id="erp-user-menu" style="display: none;">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Sign Out</button>
                    </form>
                </div>
            </div>

            <div class="erp-school-logo-wrap">
                @if (!empty($header['schoolLogo']))
                    <img src="{{ $header['schoolLogo'] }}" alt="School Logo" class="erp-school-logo">
                @else
                    <div class="erp-school-logo-fallback" title="{{ $header['schoolName'] ?? '' }}">
                        {{ strtoupper(substr($header['schoolShortName'] ?: ($header['schoolName'] ?? 'S'), 0, 3)) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="erp-header-meta">
        <span class="erp-meta-unit" title="{{ $header['unitLabel'] ?? '' }}">
            {{ $header['unitLabel'] ?? '' }}
        </span>
    </div>
</header>
