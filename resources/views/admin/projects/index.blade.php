@extends('layouts.app')

@section('title', 'إدارة المشاريع')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-project-diagram me-2"></i>إدارة المشاريع</h2>
    <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>إضافة مشروع جديد
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($projects->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>اسم المشروع</th>
                            <th>العميل</th>
                            <th>سعر الساعة</th>
                            <th>الحالة</th>
                            <th>إجمالي الساعات</th>
                            <th>إجمالي المبلغ</th>
                            <th>تاريخ الإنشاء</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($projects as $project)
                        <tr>
                            <td>
                                <div>
                                    <strong>{{ $project->name }}</strong>
                                    @if($project->description)
                                        <br><small class="text-muted">{{ Str::limit($project->description, 50) }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $project->client->name }}</td>
                            <td>{{ number_format($project->hourly_rate, 2) }} ج.م</td>
                            <td>
                                @if($project->status == 'active')
                                    <span class="badge bg-success">نشط</span>
                                @elseif($project->status == 'completed')
                                    <span class="badge bg-primary">مكتمل</span>
                                @else
                                    <span class="badge bg-warning">متوقف</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $project->time_entries_sum_hours_worked ?? 0 }} ساعة</span>
                            </td>
                            <td>
                                <strong>{{ number_format($project->time_entries_sum_total_amount ?? 0, 2) }} ج.م</strong>
                            </td>
                            <td>{{ $project->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.projects.show', $project) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا المشروع؟')">
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
                {{ $projects->links() }}
            </div>
        @else
            <div class="text-center text-muted py-5">
                <i class="fas fa-project-diagram fa-3x mb-3"></i>
                <h4>لا توجد مشاريع حالياً</h4>
                <p>ابدأ بإضافة مشروع جديد</p>
                <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>إضافة مشروع جديد
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
