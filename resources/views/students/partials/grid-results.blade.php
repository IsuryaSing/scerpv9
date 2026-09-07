@php
    $sortLink = function (string $column) use ($sort, $direction) {
        $nextDirection = ($sort === $column && $direction === 'asc') ? 'desc' : 'asc';

        return [
            'sort' => $column,
            'dir' => $nextDirection,
            'page' => 1,
        ];
    };

    $classLabel = function (?string $code) use ($classes) {
        if (! $code) {
            return '-';
        }

        return collect($classes)->firstWhere('code', $code)['name'] ?? $code;
    };
@endphp

<div class="erp-table-wrap">
    <table class="erp-table erp-grid-table">
        <thead>
            <tr>
                <th>
                    <a href="#" class="erp-grid-sort-link" data-erp-grid-sort="admission_no" data-erp-grid-dir="{{ $sortLink('admission_no')['dir'] }}">
                        Admission No
                        <span class="erp-sort-icons">
                            <i class="fa fa-caret-up{{ $sort === 'admission_no' && $direction === 'asc' ? ' is-active' : '' }}"></i>
                            <i class="fa fa-caret-down{{ $sort === 'admission_no' && $direction === 'desc' ? ' is-active' : '' }}"></i>
                        </span>
                    </a>
                </th>
                <th>
                    <a href="#" class="erp-grid-sort-link" data-erp-grid-sort="student_code" data-erp-grid-dir="{{ $sortLink('student_code')['dir'] }}">
                        Student Code
                        <span class="erp-sort-icons">
                            <i class="fa fa-caret-up{{ $sort === 'student_code' && $direction === 'asc' ? ' is-active' : '' }}"></i>
                            <i class="fa fa-caret-down{{ $sort === 'student_code' && $direction === 'desc' ? ' is-active' : '' }}"></i>
                        </span>
                    </a>
                </th>
                <th>
                    <a href="#" class="erp-grid-sort-link" data-erp-grid-sort="full_name" data-erp-grid-dir="{{ $sortLink('full_name')['dir'] }}">
                        Student Name
                        <span class="erp-sort-icons">
                            <i class="fa fa-caret-up{{ $sort === 'full_name' && $direction === 'asc' ? ' is-active' : '' }}"></i>
                            <i class="fa fa-caret-down{{ $sort === 'full_name' && $direction === 'desc' ? ' is-active' : '' }}"></i>
                        </span>
                    </a>
                </th>
                <th>
                    <a href="#" class="erp-grid-sort-link" data-erp-grid-sort="admission_class" data-erp-grid-dir="{{ $sortLink('admission_class')['dir'] }}">
                        Class
                        <span class="erp-sort-icons">
                            <i class="fa fa-caret-up{{ $sort === 'admission_class' && $direction === 'asc' ? ' is-active' : '' }}"></i>
                            <i class="fa fa-caret-down{{ $sort === 'admission_class' && $direction === 'desc' ? ' is-active' : '' }}"></i>
                        </span>
                    </a>
                </th>
                <th>
                    <a href="#" class="erp-grid-sort-link" data-erp-grid-sort="status" data-erp-grid-dir="{{ $sortLink('status')['dir'] }}">
                        Status
                        <span class="erp-sort-icons">
                            <i class="fa fa-caret-up{{ $sort === 'status' && $direction === 'asc' ? ' is-active' : '' }}"></i>
                            <i class="fa fa-caret-down{{ $sort === 'status' && $direction === 'desc' ? ' is-active' : '' }}"></i>
                        </span>
                    </a>
                </th>
            </tr>
        </thead>
        <tbody>
            @forelse ($students as $student)
                <tr>
                    <td>
                        <span class="erp-grid-link">{{ $student->admission_no ?: '-' }}</span>
                    </td>
                    <td>
                        <span class="erp-grid-link">{{ $student->student_code ?: '-' }}</span>
                    </td>
                    <td>{{ $student->full_name }}</td>
                    <td>{{ $classLabel($student->admission_class) }}</td>
                    <td>{{ $student->status ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="erp-empty-row">No students found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@include('partials.erp-grid-footer', ['paginator' => $students])
