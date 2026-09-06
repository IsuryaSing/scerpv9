<?php

namespace App\Http\Controllers;

use App\Models\FileMasterErp;
use App\Models\UserMaster;
use App\Services\ErpHeaderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ErpHeaderController extends Controller
{
    public function __construct(
        protected ErpHeaderService $headerService
    ) {
    }

    public function search(Request $request)
    {
        $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $userCode = (string) $request->session()->get('auth_user_id', Auth::user()?->user_id);

        $results = FileMasterErp::searchForUser($userCode, (string) $request->query('q', ''))
            ->map(function ($file) {
                $label = trim($file->file_sname ?: str_replace('_', ' ', $file->file_name));

                return [
                    't_code' => $file->t_code,
                    'file_name' => $file->file_name,
                    'label' => $file->t_code . ' - ' . $label,
                    'url' => $this->normalizeFlowPath($file->flow_path),
                ];
            })
            ->values();

        return response()->json([
            'results' => $results,
        ]);
    }

    public function changeUnit(Request $request)
    {
        $request->validate([
            'unit' => ['required'],
        ]);

        $userName = (string) $request->session()->get('auth_user_name', Auth::user()?->user_name);
        $unitCode = (string) $request->input('unit');

        $user = UserMaster::query()
            ->where('user_name', $userName)
            ->where('unit_cd', $unitCode)
            ->first();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'User does not exist for the selected unit.',
            ], 422);
        }

        if (! $user->isActive()) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is inactive for the selected unit.',
            ], 422);
        }

        if (! $user->isWithinValidityPeriod()) {
            if ($user->valid_from && now()->startOfDay()->lt($user->valid_from->copy()->startOfDay())) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your account is not active yet for the selected unit.',
                ], 422);
            }

            return response()->json([
                'success' => false,
                'message' => 'Your account validity has expired for the selected unit.',
            ], 422);
        }

        Auth::login($user);
        $request->session()->put([
            'selected_unit' => $unitCode,
            'auth_user_id' => $user->user_id,
            'auth_user_name' => $user->user_name,
            'auth_emp_id' => $user->emp_id,
            'auth_emp_name' => $user->emp_name,
        ]);

        return response()->json([
            'success' => true,
            'redirect' => route('selectmodule'),
        ]);
    }

    public function changeSessionYear(Request $request)
    {
        $request->validate([
            'session_year' => ['required'],
        ]);

        $request->session()->put('selected_session_year', (string) $request->input('session_year'));

        return response()->json([
            'success' => true,
        ]);
    }

    protected function normalizeFlowPath(?string $flowPath): string
    {
        if (! $flowPath || $flowPath === '#') {
            return '#';
        }

        if (str_starts_with($flowPath, 'http://') || str_starts_with($flowPath, 'https://')) {
            return $flowPath;
        }

        return url($flowPath);
    }
}
