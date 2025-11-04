@extends('layouts.app')

@section('title', 'إدارة سجلات الوقت')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-clock me-2"></i>إدارة سجلات الوقت</h2>
    <a href="{{ route('admin.time-entries.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>إضافة سجل وقت جديد
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($timeEntries->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>التاريخ</th>
                            <th>المشروع</th>
                            <th>المهمة</th>
                            <th>العميل</th>
                            <th>المطور</th>
                            <th>وقت البداية</th>
                            <th>وقت النهاية</th>
                            <th>الساعات</th>
                            <th>المبلغ</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($timeEntries as $entry)
                        <tr>
                            <td>{{ $entry->date->format('Y-m-d') }}</td>
                            <td>{{ $entry->project->name }}</td>
                            <td>
                                @if($entry->task)
                                    <a href="{{ route('admin.tasks.show', $entry->task) }}" class="text-decoration-none">
                                        <i class="fas fa-tasks me-1"></i>{{ $entry->task->title }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $entry->project->client->name }}</td>
                            <td>{{ $entry->user->name }}</td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $entry->start_time }}</span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $entry->end_time }}</span>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $entry->hours_worked }} ساعة</span>
                            </td>
                            <td>
                                <strong>{{ number_format($entry->total_amount, 2) }} ج.م</strong>
                            </td>
                            <td>
                                @if($entry->status == 'approved')
                                    <span class="badge bg-success">موافق عليه</span>
                                @elseif($entry->status == 'rejected')
                                    <span class="badge bg-danger">مرفوض</span>
                                @else
                                    <span class="badge bg-warning">في الانتظار</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.time-entries.show', $entry) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.time-entries.edit', $entry) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    @if($entry->status == 'pending')
                                        <form method="POST" action="{{ route('admin.time-entries.approve', $entry) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="موافقة">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.time-entries.reject', $entry) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="رفض">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <form method="POST" action="{{ route('admin.time-entries.destroy', $entry) }}" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا السجل؟')">
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
                {{ $timeEntries->links() }}
            </div>
        @else
            <div class="text-center text-muted py-5">
                <i class="fas fa-clock fa-3x mb-3"></i>
                <h4>لا توجد سجلات وقت حالياً</h4>
                <p>ابدأ بإضافة سجل وقت جديد</p>
                <a href="{{ route('admin.time-entries.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>إضافة سجل وقت جديد
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
