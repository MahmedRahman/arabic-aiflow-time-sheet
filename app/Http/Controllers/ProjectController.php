<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Client;
use App\Models\User;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('client')->latest()->paginate(20);
        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        $clients = Client::all();
        $users = User::where('role', 'employee')->get();
        return view('admin.projects.create', compact('clients', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'hourly_rate' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,completed,on_hold',
            'users' => 'nullable|array',
            'users.*' => 'exists:users,id',
            'hourly_rates' => 'nullable|array',
            'hourly_rates.*' => 'numeric|min:0',
        ]);

        $project = Project::create($request->only([
            'client_id', 'name', 'description', 'hourly_rate', 'status'
        ]));

        // إضافة الموظفين مع أسعار الساعة
        if ($request->has('users') && is_array($request->users)) {
            $usersData = [];
            foreach ($request->users as $index => $userId) {
                if (isset($request->hourly_rates[$index]) && $request->hourly_rates[$index] > 0) {
                    $usersData[$userId] = ['hourly_rate' => $request->hourly_rates[$index]];
                }
            }
            if (!empty($usersData)) {
                $project->users()->attach($usersData);
            }
        }

        return redirect()->route('admin.projects.index')
            ->with('success', 'تم إنشاء المشروع بنجاح.');
    }

    public function show(Project $project)
    {
        $project->load(['client', 'timeEntries.user', 'users']);
        return view('admin.projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $clients = Client::all();
        $users = User::where('role', 'employee')->get();
        $project->load('users');
        return view('admin.projects.edit', compact('project', 'clients', 'users'));
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'hourly_rate' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,completed,on_hold',
            'users' => 'nullable|array',
            'users.*' => 'exists:users,id',
            'hourly_rates' => 'nullable|array',
            'hourly_rates.*' => 'numeric|min:0',
        ]);

        $project->update($request->only([
            'client_id', 'name', 'description', 'hourly_rate', 'status'
        ]));

        // تحديث الموظفين مع أسعار الساعة
        if ($request->has('users') && is_array($request->users)) {
            $usersData = [];
            foreach ($request->users as $index => $userId) {
                // تجاهل القيم الفارغة
                if (!empty($userId) && isset($request->hourly_rates[$index]) && $request->hourly_rates[$index] > 0) {
                    $usersData[$userId] = ['hourly_rate' => $request->hourly_rates[$index]];
                }
            }
            $project->users()->sync($usersData);
        } else {
            // إذا لم يتم إرسال أي موظفين، احذف جميع الموظفين
            $project->users()->detach();
        }

        return redirect()->route('admin.projects.index')
            ->with('success', 'تم تحديث المشروع بنجاح.');
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('admin.projects.index')
            ->with('success', 'تم حذف المشروع بنجاح.');
    }
}
