<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * عرض قائمة الموظفين
     */
    public function index()
    {
        // عرض جميع المستخدمين الذين لديهم دور admin أو employee
        $users = User::whereIn('role', ['admin', 'employee'])
            ->withCount(['timeEntries', 'tasks', 'projects'])
            ->latest()
            ->paginate(20);
        
        // إحصائيات
        $stats = [
            'total_users' => User::whereIn('role', ['admin', 'employee'])->count(),
            'total_time_entries' => \App\Models\TimeEntry::whereHas('user', function($query) {
                $query->whereIn('role', ['admin', 'employee']);
            })->count(),
            'total_tasks' => \App\Models\Task::whereHas('user', function($query) {
                $query->whereIn('role', ['admin', 'employee']);
            })->count(),
            'total_projects' => \App\Models\Project::whereHas('users', function($query) {
                $query->whereIn('role', ['admin', 'employee']);
            })->distinct()->count(),
        ];
        
        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * عرض نموذج إنشاء موظف جديد
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * حفظ موظف جديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|confirmed',
            'role' => 'required|in:admin,employee',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'تم إنشاء الموظف بنجاح.');
    }

    /**
     * عرض تفاصيل موظف
     */
    public function show(User $user)
    {
        $user->load(['timeEntries.project.client', 'tasks.project']);
        return view('admin.users.show', compact('user'));
    }

    /**
     * عرض نموذج تعديل موظف
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * تحديث بيانات موظف
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|confirmed',
            'role' => 'required|in:admin,employee',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'تم تحديث بيانات الموظف بنجاح.');
    }

    /**
     * حذف موظف
     */
    public function destroy(User $user)
    {
        // منع حذف المستخدم الحالي
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'لا يمكنك حذف حسابك الخاص.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'تم حذف الموظف بنجاح.');
    }
}

