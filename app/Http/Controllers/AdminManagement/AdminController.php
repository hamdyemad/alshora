<?php

namespace App\Http\Controllers\AdminManagement;

use App\Http\Controllers\Controller;
use App\Services\AdminService;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function __construct(
        protected AdminService $adminService,
        protected RoleService $roleService
    ) {
        $this->middleware('can:admins.view')->only(['index', 'show']);
        $this->middleware('can:admins.create')->only(['create', 'store']);
        $this->middleware('can:admins.edit')->only(['edit', 'update']);
        $this->middleware('can:admins.delete')->only(['destroy']);
    }

    /**
     * Display a listing of admins
     */
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'is_blocked' => $request->input('is_blocked'),
            'created_date_from' => $request->input('created_date_from'),
            'created_date_to' => $request->input('created_date_to'),
        ];

        $admins = $this->adminService->getAll($filters, 10);
        
        return view('pages.admin-management.admins.index', compact('admins', 'filters'));
    }

    /**
     * Show the form for creating a new admin
     */
    public function create()
    {
        $roles = $this->roleService->getAll([], 0);
        return view('pages.admin-management.admins.form', compact('roles'));
    }

    /**
     * Store a newly created admin
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id',
            'is_blocked' => 'boolean',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            $data = $validator->validated();
            $data['is_blocked'] = $request->has('is_blocked') ? true : false;
            $admin = $this->adminService->createAdmin($data);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('admin.created_successfully'),
                    'redirect' => route('admin.admin-management.admins.index')
                ]);
            }

            return redirect()->route('admin.admin-management.admins.index')
                           ->with('success', __('admin.created_successfully'));
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('admin.error_creating') . ': ' . $e->getMessage()
                ], 422);
            }

            return back()->withInput()
                        ->with('error', __('admin.error_creating') . ': ' . $e->getMessage());
        }
    }

    /**
     * Display the specified admin
     */
    public function show(string $id)
    {
        try {
            $admin = $this->adminService->getAdminById($id);
            return view('pages.admin-management.admins.view', compact('admin'));
        } catch (\Exception $e) {
            return back()->with('error', __('admin.not_found'));
        }
    }

    /**
     * Show the form for editing the specified admin
     */
    public function edit(string $id)
    {
        try {
            $admin = $this->adminService->getAdminById($id);
            $roles = $this->roleService->getAll([], 0);
            
            return view('pages.admin-management.admins.form', compact('admin', 'roles'));
        } catch (\Exception $e) {
            return back()->with('error', __('admin.not_found'));
        }
    }

    /**
     * Update the specified admin
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id',
            'is_blocked' => 'boolean',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            $admin = $this->adminService->getAdminById($id);
            $data = $validator->validated();
            $data['is_blocked'] = $request->has('is_blocked') ? true : false;
            $this->adminService->updateAdmin($admin, $data);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('admin.updated_successfully'),
                    'redirect' => route('admin.admin-management.admins.index')
                ]);
            }

            return redirect()->route('admin.admin-management.admins.index')
                           ->with('success', __('admin.updated_successfully'));
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('admin.error_updating') . ': ' . $e->getMessage()
                ], 422);
            }

            return back()->withInput()
                        ->with('error', __('admin.error_updating') . ': ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified admin
     */
    public function destroy(string $id)
    {
        try {
            $admin = $this->adminService->getAdminById($id);
            $this->adminService->deleteAdmin($admin);

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('admin.deleted_successfully')
                ]);
            }

            return redirect()->route('admin.admin-management.admins.index')
                           ->with('success', __('admin.deleted_successfully'));
        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('admin.error_deleting') . ': ' . $e->getMessage()
                ], 422);
            }

            return back()->with('error', __('admin.error_deleting') . ': ' . $e->getMessage());
        }
    }

    /**
     * Toggle admin blocked status
     */
    public function toggleBlocked(string $id)
    {
        try {
            $admin = $this->adminService->getAdminById($id);
            $this->adminService->toggleBlocked($admin);

            return response()->json([
                'success' => true,
                'message' => __('admin.status_updated_successfully'),
                'is_blocked' => $admin->is_blocked
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('admin.error_updating_status') . ': ' . $e->getMessage()
            ], 422);
        }
    }
}
