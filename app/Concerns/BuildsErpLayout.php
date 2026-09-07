<?php

namespace App\Concerns;

use App\Services\ErpHeaderService;
use App\Services\ErpMenuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait BuildsErpLayout
{
    protected function erpLayoutData(Request $request, ?string $module = null): array
    {
        $activeModule = $module ?: (string) $request->session()->get('active_module', 'MAS');
        $userCode = (string) $request->session()->get('auth_user_id', Auth::user()?->user_id);

        /** @var ErpHeaderService $headerService */
        $headerService = app(ErpHeaderService::class);
        /** @var ErpMenuService $menuService */
        $menuService = app(ErpMenuService::class);

        if ($activeModule) {
            $request->session()->put('active_module', $activeModule);
        }

        return [
            'header' => $headerService->build($request),
            'sidebarMenu' => $menuService->buildSidebarMenu($userCode, $activeModule),
            'activeModule' => $activeModule,
            'moduleLabel' => $request->session()->get(
                'active_module_label',
                $menuService->moduleLabel($activeModule)
            ),
        ];
    }
}
