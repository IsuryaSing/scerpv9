<?php

namespace App\Http\Controllers;

use App\Models\UserPriv;
use App\Services\ErpHeaderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModuleController extends Controller
{
    public function __construct(
        protected ErpHeaderService $headerService
    ) {
    }

    public function setActiveModule(Request $request, string $module)
    {
        $moduleCode = strtoupper(trim($module));
        $userCode = (string) $request->session()->get('auth_user_id', Auth::user()?->user_id);

        $allowedModules = UserPriv::modulesForUser($userCode);

        if (! $allowedModules->contains($moduleCode)) {
            abort(403, 'You do not have access to this module.');
        }

        $meta = UserPriv::moduleMeta();
        $moduleLabel = $meta[$moduleCode]['label'] ?? $moduleCode;

        $request->session()->put([
            'active_module' => $moduleCode,
            'active_module_label' => $moduleLabel,
        ]);

        return redirect()->route('dashboard');
    }
}
