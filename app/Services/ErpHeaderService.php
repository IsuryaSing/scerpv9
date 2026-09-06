<?php

namespace App\Services;

use App\Models\School;
use App\Models\Unit;
use App\Models\UserMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ErpHeaderService
{
    public function build(?Request $request = null): array
    {
        $request ??= request();
        $user = Auth::user();
        $unitCode = (string) $request->session()->get('selected_unit', $user?->unit_cd);
        $unit = Unit::query()->find($unitCode);
        $school = $unit?->comp_code
            ? School::query()->find($unit->comp_code)
            : School::query()->first();

        $sessionYearId = (string) $request->session()->get('selected_session_year');
        $userName = (string) $request->session()->get('auth_user_name', $user?->user_name);

        return [
            'schoolName' => $school?->name ?: ($unit?->name ?: config('app.name', 'School ERP')),
            'schoolShortName' => $school?->short_name ?: '',
            'schoolLogo' => $this->resolveSchoolLogo($school?->logo),
            'unitCode' => $unitCode,
            'unitLabel' => $unit ? $unit->name . ' (' . $unit->code . ')' : $unitCode,
            'sessionYearId' => $sessionYearId,
            'sessionYearLabel' => $this->formatSessionYearLabel($sessionYearId),
            'sessionYears' => $this->sessionYearOptions(),
            'userName' => $userName,
            'availableUnits' => $this->availableUnits($userName),
        ];
    }

    public function formatSessionYearLabel(?string $sessionYearId): string
    {
        if (! $sessionYearId) {
            return '';
        }

        $endYear = (int) $sessionYearId;
        $startYear = $endYear - 1;

        return sprintf('%02d-%02d', $startYear % 100, $endYear % 100);
    }

    public function sessionYearOptions(): array
    {
        $current = $this->currentSessionYear();
        $currentId = (int) $current['id'];

        return [
            [
                'id' => (string) ($currentId - 1),
                'name' => $this->formatSessionYearLabel((string) ($currentId - 1)),
            ],
            [
                'id' => (string) $currentId,
                'name' => $this->formatSessionYearLabel((string) $currentId),
            ],
            [
                'id' => (string) ($currentId + 1),
                'name' => $this->formatSessionYearLabel((string) ($currentId + 1)),
            ],
        ];
    }

    public function currentSessionYear(): array
    {
        $year = (int) date('Y');
        $month = (int) date('n');

        if ($month >= 4) {
            return [
                'id' => (string) ($year + 1),
                'name' => $year . '-' . ($year + 1),
            ];
        }

        return [
            'id' => (string) $year,
            'name' => ($year - 1) . '-' . $year,
        ];
    }

    public function availableUnits(string $userName): array
    {
        $unitCodes = UserMaster::query()
            ->where('user_name', $userName)
            ->where('validity', 'Y')
            ->pluck('unit_cd')
            ->unique()
            ->values();

        if ($unitCodes->isEmpty()) {
            return Unit::query()
                ->orderBy('name')
                ->get(['code', 'name'])
                ->map(fn (Unit $unit) => [
                    'id' => (string) $unit->code,
                    'name' => $unit->name . ' (' . $unit->code . ')',
                ])
                ->all();
        }

        return Unit::query()
            ->whereIn('code', $unitCodes->all())
            ->orderBy('name')
            ->get(['code', 'name'])
            ->map(fn (Unit $unit) => [
                'id' => (string) $unit->code,
                'name' => $unit->name . ' (' . $unit->code . ')',
            ])
            ->all();
    }

    public function resolveSchoolLogo(?string $logo): ?string
    {
        if (! $logo) {
            return null;
        }

        if (str_starts_with($logo, 'http://') || str_starts_with($logo, 'https://')) {
            return $logo;
        }

        $normalized = ltrim($logo, '/');

        if (is_file(public_path($normalized))) {
            return asset($normalized);
        }

        if (is_file(public_path('storage/' . $normalized))) {
            return asset('storage/' . $normalized);
        }

        if (is_file('C:/fileuploading/' . $normalized)) {
            return asset('images/school-logo.png');
        }

        return asset($normalized);
    }
}
