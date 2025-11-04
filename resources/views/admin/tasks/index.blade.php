@extends('layouts.app')

@section('title', 'إدارة المهام')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-tasks me-2"></i>إدارة المهام</h2>
    <a href="{{ route('admin.tasks.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>إضافة مهمة جديدة
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($tasks->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>العنوان</th>
                            <th>الوصف</th>
                            <th>الوقت المتوقع</th>
                            <th>المشروع</th>
                            <th>المستخدم</th>
                            <th>الحالة</th>
                            <th>تاريخ الاستحقاق</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tasks as $task)
                        <tr>
                            <td>
                                <strong>{{ $task->title }}</strong>
                            </td>
                            <td>
                                @if($task->description)
                                    <small class="text-muted">{{ Str::limit($task->description, 50) }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ number_format($task->estimated_time, 2) }} ساعة</span>
                            </td>
                            <td>
                                @if($task->project)
                                    <a href="{{ route('admin.projects.show', $task->project) }}" class="text-decoration-none">
                                        {{ $task->project->name }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($task->user)
                                    {{ $task->user->name }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                {!! $task->getStatusBadge() !!}
                            </td>
                            <td>
                                @if($task->due_date)
                                    {{ $task->due_date->format('Y-m-d') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.tasks.show', $task) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.tasks.edit', $task) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.tasks.destroy', $task) }}" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه المهمة؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center">
                {{ $tasks->links() }}
            </div>
        @else
            <div class="text-center text-muted py-5">
                <i class="fas fa-tasks fa-3x mb-3"></i>
                <h4>لا توجد مهام حالياً</h4>
                <p>ابدأ بإضافة مهمة جديدة</p>
                <a href="{{ route('admin.tasks.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>إضافة مهمة جديدة
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

