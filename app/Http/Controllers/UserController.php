<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Menu;
use App\Models\Permission;
use App\Models\Role;
use App\Models\UserAccessLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10); // Default to 10 if not specified

        $users = User::with('role')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhereHas('role', function ($roleQuery) use ($search) {
                            $roleQuery->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->paginate($perPage)
            ->appends([
                'search' => $search,
                'per_page' => $perPage
            ]);

        $roles = Role::all();

        return view('pages/settings/users-management', [
            'users' => $users,
            'roles' => $roles,
            'perPage' => $perPage
        ]);
    }

    public function indexNew(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10); // Default to 10 if not specified

        $users = User::with('role')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhereHas('role', function ($roleQuery) use ($search) {
                            $roleQuery->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->paginate($perPage)
            ->appends([
                'search' => $search,
                'per_page' => $perPage
            ]);

        $roles = Role::all();
        $departments = DB::table('m_department')->where('pid', 0)->get();

        return view('pages.settings.users-management-new', compact('users', 'roles', 'perPage', 'departments'));
    }

    public function checkEmail(Request $request)
    {
        $emailExists = User::where('email', $request->email)->exists();

        return response()->json(['exists' => $emailExists]);
    }


    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
                ],
                'department' => 'required|exists:m_department,id',
                'role' => 'required|exists:roles,id',
                'employee_id' => 'required|string|max:50|unique:users',
            ], [
                'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan karakter khusus.',
            ]);

            // Debug data yang divalidasi
            logger()->info('Validated Data:', $validatedData);

            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role_id' => $validatedData['role'],
                'dep' => $validatedData['department'],
                'status' => 'Active',
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'employee_id' => $validatedData['employee_id'],
            ]);

            // Debug setelah create
            logger()->info('User Created:', $user->toArray());

            return redirect()->route('users-newManagement')->with('success', 'User berhasil dibuat.');
        } catch (\Exception $e) {
            logger()->error('Error creating user: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal membuat user: ' . $e->getMessage());
        }
    }

    public function indexEdit(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10); // Default to 10 if not specified

        $users = User::with('role')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhereHas('role', function ($roleQuery) use ($search) {
                            $roleQuery->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->paginate($perPage)
            ->appends([
                'search' => $search,
                'per_page' => $perPage
            ]);

        $roles = Role::all();

        return view('pages/settings/users-management-edit', [
            'users' => $users,
            'roles' => $roles,
            'perPage' => $perPage
        ]);
    }

    public function indexDelete(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10); // Default to 10 if not specified

        $users = User::with('role')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhereHas('role', function ($roleQuery) use ($search) {
                            $roleQuery->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->paginate($perPage)
            ->appends([
                'search' => $search,
                'per_page' => $perPage
            ]);

        $roles = Role::all();

        return view('pages/settings/users-management-delete', [
            'users' => $users,
            'roles' => $roles,
            'perPage' => $perPage
        ]);
    }

    public function indexUserAccessManagement()
    {
        $users = User::all();
        $permissions = Permission::all();
        $sidebarItems = DB::table('sidebar_items')->get(); // Fetch sidebar items

        return view('pages/settings/user-access-management', compact('users', 'permissions', 'sidebarItems'));
    }
    public function getUserPermissions($userId)
    {
        $userPermissions = DB::table('role_permission')
            ->where('role_id', $userId)
            ->pluck('permission_id')
            ->toArray();

        return response()->json(['permissions' => $userPermissions]);
    }

    public function update(Request $request, $userId)
    {
        // Clear existing permissions
        DB::table('role_permission')->where('role_id', $userId)->delete();

        // Insert new permissions
        foreach ($request->permissions as $permissionId) {
            DB::table('role_permission')->insert([
                'role_id' => $userId,
                'permission_id' => $permissionId,
            ]);
        }

        return response()->json(['message' => 'Permissions updated successfully!']);
    }

    public function accountGetData()
    {
        $users = User::select(['id', 'name', 'email', 'role'])->get();

        return response()->json($users);
    }

    public function userGetData()
    {
        $users = User::select(['id', 'name', 'email', 'role'])->get();

        return response()->json($users);
    }

    public function usergetMainMenus()
    {
        $mainMenus = Menu::select('id', 'header_menu')->distinct()->get();
        return response()->json($mainMenus);
    }

    // Method to get all menus
    public function getData(Request $request)
    {
        // Fetch all menus from m_menus
        $menus = Menu::whereNotNull('header_menu')
            ->whereNotNull('sub_menu')
            ->whereNotNull('menu')
            ->whereNotNull('kode')
            ->get();

        return response()->json($menus);
    }

    // Method to check user access for a specific menu
    public function getUserAccess(Request $request)
    {
        $userId = $request->query('user_id');
        $menuId = $request->query('menu_id');

        // Check if the user has access to the specified menu
        $hasAccess = UserAccessLevel::where('iduser', $userId)
            ->where('kode_menu', $menuId)
            ->exists();

        return response()->json(['hasAccess' => $hasAccess]);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
