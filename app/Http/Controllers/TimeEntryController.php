<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimeEntry;
use App\Models\Project;
use App\Models\User;
use App\Models\Task;

class TimeEntryController extends Controller
{
    public function index()
    {
        $timeEntries = TimeEntry::with(['project.client', 'user', 'task'])
            ->latest()
            ->paginate(20);
        return view('admin.time-entries.index', compact('timeEntries'));
    }

    public function create()
    {
        $projects = Project::with('client')->get();
        $users = User::where('role', 'admin')->get();
        $tasks = Task::with('project')->get();
        return view('admin.time-entries.create', compact('projects', 'users', 'tasks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'task_id' => 'nullable|exists:tasks,id',
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'description' => 'nullable|string',
        ]);

        $project = Project::findOrFail($request->project_id);
        
        // حساب الساعات
        $start = \Carbon\Carbon::parse($request->date . ' ' . $request->start_time);
        $end = \Carbon\Carbon::parse($request->date . ' ' . $request->end_time);
        $hoursWorked = $end->diffInHours($start, true);
        
        $totalAmount = $hoursWorked * $project->hourly_rate;

        TimeEntry::create([
            'project_id' => $request->project_id,
            'task_id' => $request->task_id,
            'user_id' => $request->user_id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'hours_worked' => $hoursWorked,
            'description' => $request->description,
            'hourly_rate' => $project->hourly_rate,
            'total_amount' => $totalAmount,
            'status' => 'pending',
        ]);

        return redirect()->route('admin.time-entries.index')
            ->with('success', 'تم إضافة سجل الوقت بنجاح.');
    }

    public function show(TimeEntry $timeEntry)
    {
        $timeEntry->load(['project.client', 'user', 'task']);
        return view('admin.time-entries.show', compact('timeEntry'));
    }

    public function edit(TimeEntry $timeEntry)
    {
        $projects = Project::with('client')->get();
        $users = User::where('role', 'admin')->get();
        $tasks = Task::with('project')->get();
        return view('admin.time-entries.edit', compact('timeEntry', 'projects', 'users', 'tasks'));
    }

    public function update(Request $request, TimeEntry $timeEntry)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'task_id' => 'nullable|exists:tasks,id',
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $project = Project::findOrFail($request->project_id);
        
        // حساب الساعات
        $start = \Carbon\Carbon::parse($request->date . ' ' . $request->start_time);
        $end = \Carbon\Carbon::parse($request->date . ' ' . $request->end_time);
        $hoursWorked = $end->diffInHours($start, true);
        
        $totalAmount = $hoursWorked * $project->hourly_rate;

        $timeEntry->update([
            'project_id' => $request->project_id,
            'task_id' => $request->task_id,
            'user_id' => $request->user_id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'hours_worked' => $hoursWorked,
            'description' => $request->description,
            'hourly_rate' => $project->hourly_rate,
            'total_amount' => $totalAmount,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.time-entries.index')
            ->with('success', 'تم تحديث سجل الوقت بنجاح.');
    }

    public function destroy(TimeEntry $timeEntry)
    {
        $timeEntry->delete();

        return redirect()->route('admin.time-entries.index')
            ->with('success', 'تم حذف سجل الوقت بنجاح.');
    }

    public function approve(TimeEntry $timeEntry)
    {
        $timeEntry->update(['status' => 'approved']);
        
        return redirect()->back()
            ->with('success', 'تم الموافقة على سجل الوقت.');
    }

    public function reject(TimeEntry $timeEntry)
    {
        $timeEntry->update(['status' => 'rejected']);
        
        return redirect()->back()
            ->with('success', 'تم رفض سجل الوقت.');
    }
}
