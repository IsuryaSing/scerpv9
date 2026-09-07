<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'School ERP'))</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('css/erp-header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/erp-sidebar.css') }}">
    @stack('styles')
</head>
<body class="erp-body erp-body-app">
    @include('partials.erp-header', ['header' => $header ?? []])

    <div class="erp-app-shell">
        @include('partials.erp-sidebar', [
            'sidebarMenu' => $sidebarMenu ?? [],
            'activeModule' => $activeModule ?? session('active_module'),
            'moduleLabel' => $moduleLabel ?? session('active_module_label'),
        ])

        <main class="erp-main-content">
            @yield('content')
        </main>
    </div>

    <script>
        window.erpHeaderConfig = {
            searchUrl: @json(route('erp.search')),
            changeUnitUrl: @json(route('erp.change-unit')),
            changeSessionUrl: @json(route('erp.change-session')),
        };
    </script>
    <script src="{{ asset('js/erp-header.js') }}"></script>
    <script src="{{ asset('js/erp-sidebar.js') }}"></script>
    @stack('scripts')
</body>
</html>
