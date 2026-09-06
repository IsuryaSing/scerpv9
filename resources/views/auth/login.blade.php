<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign In - {{ config('app.name', 'School ERP') }}</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="login-page">
        <div class="login-card">
            <div class="login-visual">
                <img
                    src="{{ asset('images/software_eng.png') }}"
                    alt="Surya.erp"
                    class="brand-logo"
                >
                <div class="illustration-wrap">
                    <img
                        src="{{ asset('images/school_erp_login.png') }}"
                        alt="School ERP"
                    >
                </div>
            </div>

            <div class="login-form-panel">
                <div class="company-logo-wrap">
                    <img
                        src="{{ asset('images/software_eng.png') }}"
                        alt="{{ config('app.name', 'School ERP') }}"
                    >
                </div>

                @if ($errors->any())
                    <div class="alert-error">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form class="login-form" method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label class="form-label" for="username">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                            User Name
                        </label>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            class="form-control"
                            value="{{ old('username') }}"
                            autocomplete="username"
                            required
                            autofocus
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="unit">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 21V9l8-5 8 5v12H4zm2-2h12v-9.7L12 5.8 6 9.3V19zm3-2h2v-5H9v5zm4 0h2v-5h-2v5z"/></svg>
                            Unit
                        </label>
                        <select id="unit" name="unit" class="form-control" required>
                            <option value="">Select Unit</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit['id'] }}" @selected(old('unit') == $unit['id'])>
                                    {{ $unit['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="session_year">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19 4h-1V2h-2v2H8V2H6v2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z"/></svg>
                            Session Year
                        </label>
                        <select id="session_year" name="session_year" class="form-control" required>
                            <option value="">Select Session Year</option>
                            @foreach ($sessionYears as $year)
                                <option
                                    value="{{ $year['id'] }}"
                                    @selected(old('session_year', $currentSessionYearId) == $year['id'])
                                >
                                    {{ $year['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18 8h-1V6a5 5 0 0 0-10 0v2H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V10a2 2 0 0 0-2-2zm-7 8.7V17h2v-.3a2 2 0 0 0 1-1.7 2 2 0 1 0-4 0 2 2 0 0 0 1 1.7zM9 8V6a3 3 0 0 1 6 0v2H9z"/></svg>
                            Password
                        </label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control"
                            autocomplete="current-password"
                            required
                        >
                    </div>

                    <button type="submit" class="btn-signin">Sign In</button>

                    <a href="#" class="change-password">Change Password</a>
                </form>
            </div>
        </div>
    </div>

    <footer class="login-footer">
        Copyright &copy; {{ date('Y') }} Surya.erp Designed by Surya Prakash Singh All rights reserved.
    </footer>

    <div class="version-badge">Current Version: 1.02</div>

    <script>
        (function () {
            const lookupUrl = @json(route('login.lookup-user'));
            const currentSessionYearId = @json($currentSessionYearId);
            const usernameInput = document.getElementById('username');
            const unitSelect = document.getElementById('unit');
            const sessionYearSelect = document.getElementById('session_year');
            let lookupTimer = null;

            function setSelectValue(select, value) {
                if (!select || value === null || value === undefined || value === '') {
                    return;
                }

                const option = Array.from(select.options).find(function (item) {
                    return item.value === String(value);
                });

                if (option) {
                    select.value = option.value;
                }
            }

            function setCurrentSessionYear() {
                setSelectValue(sessionYearSelect, currentSessionYearId);
            }

            function lookupUser() {
                const username = usernameInput.value.trim();

                if (!username) {
                    return;
                }

                fetch(lookupUrl + '?username=' + encodeURIComponent(username), {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(function (response) {
                        return response.json();
                    })
                    .then(function (data) {
                        if (!data.found) {
                            return;
                        }

                        setSelectValue(unitSelect, data.unit_cd);
                        setSelectValue(sessionYearSelect, data.session_year || currentSessionYearId);
                    })
                    .catch(function () {
                        // Ignore lookup errors on the login form.
                    });
            }

            function scheduleLookup() {
                clearTimeout(lookupTimer);
                lookupTimer = setTimeout(lookupUser, 400);
            }

            usernameInput.addEventListener('blur', lookupUser);
            usernameInput.addEventListener('input', scheduleLookup);

            setCurrentSessionYear();

            if (usernameInput.value.trim()) {
                lookupUser();
            }
        })();
    </script>
</body>
</html>
