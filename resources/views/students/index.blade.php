@extends('layouts.erp-app')

@section('title', 'Student List')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/erp-form.css') }}">
@endpush

@section('content')
    <div class="erp-page-head">
        <h1>Student List</h1>
        <a href="{{ url('/students/add') }}" class="erp-btn erp-btn-primary">
            <i class="fa fa-plus"></i> Add Student
        </a>
    </div>

    @if (session('success'))
        <div class="erp-alert erp-alert-success">{{ session('success') }}</div>
    @endif

    @php
        $gridState = [
            'sort' => $sort,
            'dir' => $direction,
            'per_page' => request('per_page', $students->perPage()),
            'page' => $students->currentPage(),
            'class' => $classFilter,
            'status' => $statusFilter,
        ];
    @endphp

    <div
        class="erp-panel erp-ajax-grid"
        data-erp-ajax-url="{{ route('students.index') }}"
        data-erp-grid-state='@json($gridState)'
    >
        <form method="GET" action="{{ route('students.index') }}" class="erp-filter-form">
            <div class="erp-filter-row">
                <label for="class">Class</label>
                <select name="class" id="class" class="erp-input">
                    <option value="">All Classes</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class['code'] }}" @selected($classFilter === $class['code'])>
                            {{ $class['name'] }}
                        </option>
                    @endforeach
                </select>

                <label for="status">Status</label>
                <select name="status" id="status" class="erp-input">
                    <option value="">All Status</option>
                    <option value="Active" @selected($statusFilter === 'Active')>Active</option>
                    <option value="Inactive" @selected($statusFilter === 'Inactive')>Inactive</option>
                    <option value="Left" @selected($statusFilter === 'Left')>Left</option>
                </select>

                <button type="submit" class="erp-btn erp-btn-primary">Search</button>
                <a href="{{ route('students.index') }}" class="erp-btn erp-btn-light" data-erp-grid-reset>Reset</a>
            </div>
        </form>

        <div class="erp-toolbar">
            <a href="{{ url('/students/add') }}" class="erp-tool-btn" title="Add"><i class="fa fa-plus"></i></a>
            <button type="button" class="erp-tool-btn" title="Export Excel"><i class="fa fa-file-excel-o"></i></button>
            <button type="button" class="erp-tool-btn" title="Export PDF"><i class="fa fa-file-pdf-o"></i></button>
        </div>

        <div class="erp-grid-results" data-erp-grid-results>
            @include('students.partials.grid-results')
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/erp-grid.js') }}"></script>
@endpush
