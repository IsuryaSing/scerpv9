@php
    $sortLink = function (string $column) use ($sort, $direction) {
        $nextDirection = ($sort === $column && $direction === 'asc') ? 'desc' : 'asc';

        return [
            'sort' => $column,
            'dir' => $nextDirection,
            'page' => 1,
        ];
    };
@endphp

<div class="erp-table-wrap">
    <table class="erp-table erp-grid-table">
        <thead>
            <tr>
                <th>
                    <a
                        href="#"
                        class="erp-grid-sort-link"
                        data-erp-grid-sort="class"
                        data-erp-grid-dir="{{ $sortLink('class')['dir'] }}"
                    >
                        Class
                        <span class="erp-sort-icons">
                            <i class="fa fa-caret-up{{ $sort === 'class' && $direction === 'asc' ? ' is-active' : '' }}"></i>
                            <i class="fa fa-caret-down{{ $sort === 'class' && $direction === 'desc' ? ' is-active' : '' }}"></i>
                        </span>
                    </a>
                </th>
                <th>
                    <a
                        href="#"
                        class="erp-grid-sort-link"
                        data-erp-grid-sort="subject_code"
                        data-erp-grid-dir="{{ $sortLink('subject_code')['dir'] }}"
                    >
                        Subject Code
                        <span class="erp-sort-icons">
                            <i class="fa fa-caret-up{{ $sort === 'subject_code' && $direction === 'asc' ? ' is-active' : '' }}"></i>
                            <i class="fa fa-caret-down{{ $sort === 'subject_code' && $direction === 'desc' ? ' is-active' : '' }}"></i>
                        </span>
                    </a>
                </th>
                <th>
                    <a
                        href="#"
                        class="erp-grid-sort-link"
                        data-erp-grid-sort="subject_name"
                        data-erp-grid-dir="{{ $sortLink('subject_name')['dir'] }}"
                    >
                        Subject Name
                        <span class="erp-sort-icons">
                            <i class="fa fa-caret-up{{ $sort === 'subject_name' && $direction === 'asc' ? ' is-active' : '' }}"></i>
                            <i class="fa fa-caret-down{{ $sort === 'subject_name' && $direction === 'desc' ? ' is-active' : '' }}"></i>
                        </span>
                    </a>
                </th>
                <th class="erp-col-action">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($subjects as $subject)
                @php($className = collect($classes)->firstWhere('code', $subject->class)['name'] ?? $subject->class)
                <tr>
                    <td>
                        <a href="{{ route('subjects.form.class', $subject->class) }}" class="erp-grid-link">
                            {{ $className }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('subjects.form.class', $subject->class) }}" class="erp-grid-link">
                            {{ $subject->subject_code }}
                        </a>
                    </td>
                    <td>{{ $subject->subject_name }}</td>
                    <td class="erp-col-action">
                        <a href="{{ route('subjects.form.class', $subject->class) }}" class="erp-grid-action-btn" title="Edit">
                            <i class="fa fa-pencil"></i>
                        </a>
                        <a href="{{ route('subjects.form.class', ['class' => $subject->class, 'view' => 1]) }}" class="erp-grid-action-btn" title="View">
                            <i class="fa fa-eye"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="erp-empty-row">No subjects found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@include('partials.erp-grid-footer', ['paginator' => $subjects])
