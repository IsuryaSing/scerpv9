<?php

namespace App\Http\Controllers;

use App\Concerns\BuildsErpLayout;
use App\Models\SecControlValue;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    use BuildsErpLayout;

    public function index(Request $request)
    {
        $data = $this->studentIndexData($request);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('students.partials.grid-results', $data)->render(),
            ]);
        }

        return view('students.index', array_merge($this->erpLayoutData($request, 'STU'), $data));
    }

    protected function studentIndexData(Request $request): array
    {
        $classFilter = $request->query('class');
        $statusFilter = $request->query('status');
        $classes = SecControlValue::classTypes();

        $perPage = (int) $request->query('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100], true) ? $perPage : 10;

        $sort = $request->query('sort', 'admission_no');
        $direction = strtolower((string) $request->query('dir', 'asc')) === 'desc' ? 'desc' : 'asc';
        $sortable = ['admission_no', 'student_code', 'full_name', 'admission_class', 'status'];

        if (! in_array($sort, $sortable, true)) {
            $sort = 'admission_no';
        }

        $students = Student::query()
            ->when($classFilter, fn ($query) => $query->where('admission_class', $classFilter))
            ->when($statusFilter, fn ($query) => $query->where('status', $statusFilter))
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->withQueryString();

        return [
            'students' => $students,
            'classes' => $classes,
            'classFilter' => $classFilter,
            'statusFilter' => $statusFilter,
            'sort' => $sort,
            'direction' => $direction,
        ];
    }
}
