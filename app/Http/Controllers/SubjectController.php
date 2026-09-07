<?php

namespace App\Http\Controllers;

use App\Concerns\BuildsErpLayout;
use App\Models\SecControlValue;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    use BuildsErpLayout;

    public function index(Request $request)
    {
        $data = $this->subjectIndexData($request);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('subjects.partials.grid-results', $data)->render(),
            ]);
        }

        return view('subjects.index', array_merge($this->erpLayoutData($request, 'MAS'), $data));
    }

    protected function subjectIndexData(Request $request): array
    {
        $classFilter = $request->query('class');
        $classes = SecControlValue::classTypes();

        $perPage = (int) $request->query('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100], true) ? $perPage : 10;

        $sort = $request->query('sort', 'class');
        $direction = strtolower((string) $request->query('dir', 'asc')) === 'desc' ? 'desc' : 'asc';
        $sortable = ['class', 'subject_code', 'subject_name', 'sl_no'];

        if (! in_array($sort, $sortable, true)) {
            $sort = 'class';
        }

        $subjects = Subject::query()
            ->when($classFilter, fn ($query) => $query->where('class', $classFilter))
            ->orderBy($sort, $direction)
            ->when($sort !== 'sl_no', fn ($query) => $query->orderBy('sl_no'))
            ->paginate($perPage)
            ->withQueryString();

        return [
            'subjects' => $subjects,
            'classes' => $classes,
            'classFilter' => $classFilter,
            'sort' => $sort,
            'direction' => $direction,
        ];
    }

    public function form(Request $request, ?string $class = null)
    {
        $classes = SecControlValue::classTypes();
        $selectedClass = $class ?: $request->query('class');
        $readOnly = $request->boolean('view');

        $rows = $selectedClass
            ? Subject::query()
                ->where('class', $selectedClass)
                ->orderBy('sl_no')
                ->get()
            : collect();

        $title = $readOnly
            ? 'View Subjects'
            : ($rows->isEmpty() ? 'Add Subjects' : 'Edit Subjects');

        return view('subjects.form', array_merge($this->erpLayoutData($request, 'MAS'), [
            'classes' => $classes,
            'selectedClass' => $selectedClass,
            'rows' => $rows,
            'readOnly' => $readOnly,
            'title' => $title,
        ]));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class' => ['required', 'string', 'max:20'],
            'rows' => ['required', 'array', 'min:1'],
            'rows.*.subject_id' => ['nullable', 'integer'],
            'rows.*.sl_no' => ['required', 'integer', 'min:1'],
            'rows.*.subject_code' => ['required', 'string', 'size:2'],
            'rows.*.subject_name' => ['required', 'string', 'max:100'],
        ]);

        $userName = (string) $request->session()->get('auth_user_name', Auth::user()?->user_name);
        $class = $validated['class'];
        $submittedIds = collect($validated['rows'])
            ->pluck('subject_id')
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->all();

        DB::transaction(function () use ($validated, $class, $submittedIds, $userName) {
            $deleteQuery = Subject::query()->where('class', $class);

            if (count($submittedIds) > 0) {
                $deleteQuery->whereNotIn('subject_id', $submittedIds);
            }

            $deleteQuery->delete();

            foreach ($validated['rows'] as $row) {
                $payload = [
                    'class' => $class,
                    'subject_code' => strtoupper(trim($row['subject_code'])),
                    'subject_name' => trim($row['subject_name']),
                    'sl_no' => (int) $row['sl_no'],
                    'updated_by' => $userName,
                ];

                if (! empty($row['subject_id'])) {
                    Subject::query()
                        ->where('subject_id', $row['subject_id'])
                        ->where('class', $class)
                        ->update($payload);
                } else {
                    Subject::query()->create(array_merge($payload, [
                        'created_by' => $userName,
                    ]));
                }
            }
        });

        return redirect()
            ->route('subjects.index', ['class' => $class])
            ->with('success', 'Subjects saved successfully.');
    }
}
