<?php

namespace App\Http\Controllers;

use App\Models\UserPriv;
use App\Services\ErpHeaderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SelectModuleController extends Controller
{
    public function __construct(
        protected ErpHeaderService $headerService
    ) {
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $userCode = (string) ($request->session()->get('auth_user_id') ?: $user?->user_id);

        $moduleCodes = UserPriv::modulesForUser($userCode);
        $moduleMeta = UserPriv::moduleMeta();

        $modules = $moduleCodes
            ->map(function (string $code) use ($moduleMeta) {
                $meta = $moduleMeta[$code] ?? [
                    'label' => strtoupper($code),
                    'logo' => 'masterslogo.svg',
                ];

                return [
                    'code' => $code,
                    'label' => $meta['label'],
                    'logo' => asset('images/modules/' . $meta['logo']),
                ];
            })
            ->values()
            ->all();

        return view('selectmodule', [
            'modules' => $modules,
            'header' => $this->headerService->build($request),
        ]);
    }
}
