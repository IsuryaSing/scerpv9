<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\UserMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        $currentSessionYear = $this->currentSessionYear();

        return view('auth.login', [
            'units' => $this->units(),
            'sessionYears' => $this->sessionYears(),
            'currentSessionYearId' => $currentSessionYear['id'],
        ]);
    }

    public function lookupUser(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
        ]);

        $user = UserMaster::findByUsername($request->username);

        if (! $user) {
            return response()->json([
                'found' => false,
            ]);
        }

        $unit = Unit::query()->find($user->unit_cd);
        $currentSessionYear = $this->currentSessionYear();

        return response()->json([
            'found' => true,
            'unit_cd' => (string) $user->unit_cd,
            'unit_name' => $unit?->name,
            'session_year' => $currentSessionYear['id'],
            'session_year_label' => $currentSessionYear['name'],
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            'unit' => ['required'],
            'session_year' => ['required'],
        ]);

        $user = UserMaster::findByUsername($credentials['username']);

        if (! $user) {
            throw ValidationException::withMessages([
                'username' => 'Invalid username or password.',
            ]);
        }

        $validation = $user->validateLogin($credentials['password'], $credentials['unit']);

        if (! $validation['valid']) {
            throw ValidationException::withMessages([
                $validation['field'] => $validation['message'],
            ]);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        $request->session()->put([
            'selected_unit' => $credentials['unit'],
            'selected_session_year' => $credentials['session_year'],
            'auth_user_id' => $user->user_id,
            'auth_user_name' => $user->user_name,
            'auth_emp_id' => $user->emp_id,
            'auth_emp_name' => $user->emp_name,
        ]);

        return redirect()->intended(route('selectmodule'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    protected function units(): array
    {
        return Unit::query()
            ->orderBy('name')
            ->get(['code', 'name'])
            ->map(fn (Unit $unit) => [
                'id' => (string) $unit->code,
                'name' => $unit->name . ' (' . $unit->code . ')',
            ])
            ->all();
    }

    protected function currentSessionYear(): array
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

    protected function sessionYears(): array
    {
        $current = $this->currentSessionYear();
        $currentId = (int) $current['id'];

        return [
            [
                'id' => (string) ($currentId - 1),
                'name' => ($currentId - 2) . '-' . ($currentId - 1),
            ],
            $current,
            [
                'id' => (string) ($currentId + 1),
                'name' => $currentId . '-' . ($currentId + 1),
            ],
        ];
    }
}
