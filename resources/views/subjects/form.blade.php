@extends('layouts.erp-app')

@section('title', $title ?? 'Subjects')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/erp-form.css') }}">
@endpush

@section('content')
    <div class="erp-page-head">
        <h1>{{ $title ?? 'Add Subjects' }}</h1>
        <div class="erp-page-actions">
            @unless($readOnly)
                <button type="submit" form="subject-form" class="erp-icon-action erp-icon-save" title="Save">
                    <i class="fa fa-save"></i>
                </button>
            @endunless
            <a href="{{ route('subjects.index') }}" class="erp-icon-action erp-icon-home" title="Back">
                <i class="fa fa-home"></i>
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="erp-alert erp-alert-error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="subject-form" method="POST" action="{{ route('subjects.store') }}">
        @csrf

        <div class="erp-panel">
            <div class="erp-section-title">Header</div>
            <div class="erp-form-grid">
                <div class="erp-form-group">
                    <label for="class">Class <span class="erp-required">*</span></label>
                    <select
                        name="class"
                        id="class"
                        class="erp-input"
                        required
                        {{ $readOnly ? 'disabled' : '' }}
                        @unless($readOnly) onchange="window.location.href='{{ route('subjects.form') }}?class=' + this.value" @endunless
                    >
                        <option value="">Select</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class['code'] }}" @selected(old('class', $selectedClass) === $class['code'])>
                                {{ $class['name'] }}
                            </option>
                        @endforeach
                    </select>
                    @if ($readOnly && $selectedClass)
                        <input type="hidden" name="class" value="{{ $selectedClass }}">
                    @endif
                </div>
            </div>
        </div>

        <div class="erp-panel">
            <div class="erp-section-head">
                <div class="erp-section-title erp-section-title-inline">
                    <i class="fa fa-plus-circle"></i> Subject Details
                </div>
                @unless($readOnly)
                    <button type="button" class="erp-btn erp-btn-light" id="add-subject-row">
                        <i class="fa fa-plus"></i> Add Row
                    </button>
                @endunless
            </div>

            <div class="erp-table-wrap">
                <table class="erp-table" id="subject-details-table">
                    <thead>
                        <tr>
                            <th style="width:110px;">Subject ID</th>
                            <th style="width:90px;">Sl No</th>
                            <th style="width:120px;">Subject Code</th>
                            <th>Subject Name</th>
                            @unless($readOnly)
                                <th style="width:100px;">Action</th>
                            @endunless
                        </tr>
                    </thead>
                    <tbody id="subject-rows">
                        @php($existingRows = old('rows', $rows->map(fn ($row) => [
                            'subject_id' => $row->subject_id,
                            'sl_no' => $row->sl_no,
                            'subject_code' => $row->subject_code,
                            'subject_name' => $row->subject_name,
                        ])->all()))

                        @forelse ($existingRows as $index => $row)
                            @include('subjects.partials.row', ['index' => $index, 'row' => $row, 'readOnly' => $readOnly])
                        @empty
                            @unless($readOnly)
                                @include('subjects.partials.row', ['index' => 0, 'row' => ['subject_id' => null, 'sl_no' => 1, 'subject_code' => '', 'subject_name' => ''], 'readOnly' => false])
                            @endunless
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script src="{{ asset('js/subjects-form.js') }}"></script>
@endpush
