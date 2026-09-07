@extends('layouts.erp')

@section('title', 'ERP Modules - ' . config('app.name', 'School ERP'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/selectmodule.css') }}">
@endpush

@section('content')
    <div class="module-shell">
        <div class="module-page">
            <div class="module-title-bar">
                <h1>ERP Modules</h1>
            </div>

            <main class="module-content">
                @if (count($modules))
                    <div class="module-grid">
                        @foreach ($modules as $module)
                            <a href="{{ route('module.activate', $module['code']) }}" class="module-card module-card--{{ strtolower($module['code']) }}" data-module="{{ $module['code'] }}">
                                <div class="module-icon">
                                    <img src="{{ $module['logo'] }}" alt="{{ $module['label'] }}">
                                </div>
                                <div class="module-label">{{ $module['label'] }}</div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="module-empty">
                        No modules are assigned to your user account.
                    </div>
                @endif
            </main>
        </div>
    </div>
@endsection
