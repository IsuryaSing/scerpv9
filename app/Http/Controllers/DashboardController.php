<?php

namespace App\Http\Controllers;

use App\Services\ErpHeaderService;
use App\Services\ErpMenuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        protected ErpHeaderService $headerService,
        protected ErpMenuService $menuService
    ) {
    }

    public function index(Request $request)
    {
        $activeModule = (string) $request->session()->get('active_module', '');

        if ($activeModule === '') {
            return redirect()->route('selectmodule');
        }

        $userCode = (string) $request->session()->get('auth_user_id', Auth::user()?->user_id);
        $moduleLabel = (string) $request->session()->get(
            'active_module_label',
            $this->menuService->moduleLabel($activeModule)
        );

        return view('dashboard', [
            'header' => $this->headerService->build($request),
            'sidebarMenu' => $this->menuService->buildSidebarMenu($userCode, $activeModule),
            'activeModule' => $activeModule,
            'moduleLabel' => $moduleLabel,
        ]);
    }
}
