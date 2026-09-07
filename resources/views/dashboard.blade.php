@extends('layouts.erp-app')

@section('title', 'Dashboard - ' . ($moduleLabel ?? 'ERP'))

@section('content')
    <div class="erp-page-head">
        <h1><i class="fa fa-dashboard"></i> Dashboard</h1>
        <span class="erp-page-module">{{ $moduleLabel ?? $activeModule }}</span>
    </div>

    <div class="erp-dashboard-panel">
        <p>Welcome to the <strong>{{ $moduleLabel ?? $activeModule }}</strong> module dashboard.</p>
        <p class="erp-muted">Use the sidebar menu to open forms and reports for this module.</p>
    </div>
@endsection
