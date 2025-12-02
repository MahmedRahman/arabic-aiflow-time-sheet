<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Models\TimeEntry;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::with(['project', 'user', 'timeEntries' => function($query) {
            $query->where('is_active', true);
        }])
            ->latest()
            ->paginate(20);
        
        // حساب الوقت الإجمالي لكل مهمة
        foreach ($tasks as $task) {
            // الوقت الإجمالي من جميع الجلسات المكتملة
            $task->total_time = $task->timeEntries()
                ->where('is_active', false)
                ->sum('hours_worked');
            
            // الجلسة النشطة الحالية
            $task->active_session = $task->timeEntries()
                ->where('is_active', true)
                ->where('user_id', Auth::id())
                ->first();
            
            // جميع الجلسات المكتملة مرتبة حسب التاريخ
            $task->completed_sessions = $task->timeEntries()
                ->where('is_active', false)
                ->orderBy('date', 'desc')
                ->orderBy('start_time', 'desc')
                ->get();
            
            // عدد الجلسات الكلية
            $task->total_sessions = $task->completed_sessions->count();
        }
        
        return view('admin.tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = Project::all();
        $users = User::all();
        return view('admin.tasks.create', compact('projects', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'estimated_time' => 'required|numeric|min:0',
            'project_id' => 'nullable|exists:projects,id',
            'user_id' => 'nullable|exists:users,id',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'due_date' => 'nullable|date',
        ]);

        Task::create($request->all());

        return redirect()->route('admin.tasks.index')
            ->with('success', 'تم إنشاء المهمة بنجاح.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $task->load(['project.client', 'user']);
        return view('admin.tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $projects = Project::all();
        $users = User::all();
        return view('admin.tasks.edit', compact('task', 'projects', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'estimated_time' => 'required|numeric|min:0',
            'project_id' => 'nullable|exists:projects,id',
            'user_id' => 'nullable|exists:users,id',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'due_date' => 'nullable|date',
        ]);

        $task->update($request->all());

        return redirect()->route('admin.tasks.index')
            ->with('success', 'تم تحديث المهمة بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('admin.tasks.index')
            ->with('success', 'تم حذف المهمة بنجاح.');
    }

    /**
     * بدء تتبع الوقت للمهمة
     */
    public function startTracking(Task $task)
    {
        // التحقق من وجود جلسة نشطة لنفس المستخدم
        $activeSession = TimeEntry::where('user_id', Auth::id())
            ->where('is_active', true)
            ->first();

        if ($activeSession) {
            return response()->json([
                'success' => false,
                'message' => 'يوجد جلسة نشطة بالفعل. يرجى إيقافها أولاً.'
            ], 400);
        }

        // التحقق من أن المهمة مرتبطة بمشروع
        if (!$task->project_id) {
            return response()->json([
                'success' => false,
                'message' => 'يجب ربط المهمة بمشروع أولاً.'
            ], 400);
        }

        $project = Project::findOrFail($task->project_id);
        $now = Carbon::now();

        $timeEntry = TimeEntry::create([
            'project_id' => $task->project_id,
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'date' => $now->toDateString(),
            'start_time' => $now->format('H:i:s'),
            'end_time' => null,
            'hours_worked' => 0,
            'description' => 'تتبع تلقائي للمهمة: ' . $task->title,
            'hourly_rate' => $project->hourly_rate ?? 0,
            'total_amount' => 0,
            'status' => 'pending',
            'is_active' => true,
            'started_at' => $now,
        ]);

        // تحديث حالة المهمة إلى قيد التنفيذ
        if ($task->status === 'pending') {
            $task->update(['status' => 'in_progress']);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم بدء تتبع الوقت بنجاح.',
            'time_entry_id' => $timeEntry->id,
            'started_at' => $timeEntry->started_at->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * إيقاف تتبع الوقت للمهمة
     */
    public function stopTracking(Task $task)
    {
        $activeSession = TimeEntry::where('task_id', $task->id)
            ->where('user_id', Auth::id())
            ->where('is_active', true)
            ->first();

        if (!$activeSession) {
            return response()->json([
                'success' => false,
                'message' => 'لا توجد جلسة نشطة لهذه المهمة.'
            ], 400);
        }

        $now = Carbon::now();
        $startedAt = Carbon::parse($activeSession->started_at);
        
        // حساب الوقت بالدقائق ثم تحويله إلى ساعات
        $totalMinutes = $startedAt->diffInMinutes($now);
        $hoursWorked = round($totalMinutes / 60, 2);

        $activeSession->update([
            'end_time' => $now->format('H:i:s'),
            'hours_worked' => round($hoursWorked, 2),
            'total_amount' => round($hoursWorked * $activeSession->hourly_rate, 2),
            'is_active' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إيقاف تتبع الوقت بنجاح.',
            'hours_worked' => round($hoursWorked, 2),
            'total_amount' => round($hoursWorked * $activeSession->hourly_rate, 2),
        ]);
    }

    /**
     * الحصول على معلومات الجلسة النشطة
     */
    public function getActiveSession(Task $task)
    {
        $activeSession = TimeEntry::where('task_id', $task->id)
            ->where('user_id', Auth::id())
            ->where('is_active', true)
            ->first();

        if (!$activeSession) {
            return response()->json([
                'success' => false,
                'has_active_session' => false,
            ]);
        }

        $now = Carbon::now();
        $startedAt = Carbon::parse($activeSession->started_at);
        $elapsedMinutes = $startedAt->diffInMinutes($now);
        $elapsedHours = round($elapsedMinutes / 60, 2);

        return response()->json([
            'success' => true,
            'has_active_session' => true,
            'time_entry_id' => $activeSession->id,
            'started_at' => $activeSession->started_at->format('Y-m-d H:i:s'),
            'elapsed_hours' => $elapsedHours,
            'elapsed_minutes' => $elapsedMinutes,
        ]);
    }
}
