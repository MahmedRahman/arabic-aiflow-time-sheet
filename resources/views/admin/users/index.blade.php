@extends('layouts.app')

@section('title', 'إدارة الموظفين')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-user-tie me-2"></i>إدارة الموظفين</h2>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>إضافة موظف جديد
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- إحصائيات سريعة -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">{{ $stats['total_users'] }}</h4>
                        <p class="mb-0 small">إجمالي الموظفين</p>
                    </div>
                    <div>
                        <i class="fas fa-user-tie fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">{{ $stats['total_time_entries'] }}</h4>
                        <p class="mb-0 small">سجلات الوقت</p>
                    </div>
                    <div>
                        <i class="fas fa-clock fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">{{ $stats['total_tasks'] }}</h4>
                        <p class="mb-0 small">إجمالي المهام</p>
                    </div>
                    <div>
                        <i class="fas fa-tasks fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">{{ $stats['total_projects'] }}</h4>
                        <p class="mb-0 small">المشاريع</p>
                    </div>
                    <div>
                        <i class="fas fa-project-diagram fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-light">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-list me-2"></i>قائمة الموظفين
            </h5>
            <span class="badge bg-primary">{{ $users->total() }} موظف</span>
        </div>
    </div>
    <div class="card-body">
        @if($users->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 20%;">الموظف</th>
                            <th style="width: 20%;">البريد الإلكتروني</th>
                            <th style="width: 10%;">الدور</th>
                            <th style="width: 12%;" class="text-center">سجلات الوقت</th>
                            <th style="width: 12%;" class="text-center">المهام</th>
                            <th style="width: 12%;" class="text-center">المشاريع</th>
                            <th style="width: 9%;">تاريخ الإنشاء</th>
                            <th style="width: 10%;" class="text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $index => $user)
                        <tr>
                            <td class="text-muted">{{ $users->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center" 
                                         style="width: 45px; height: 45px; font-weight: 600; font-size: 1.1rem;">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <strong class="d-block">{{ $user->name }}</strong>
                                        @if($user->id === auth()->id())
                                            <span class="badge bg-secondary" style="font-size: 0.7rem;">
                                                <i class="fas fa-user-check me-1"></i>أنت
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <a href="mailto:{{ $user->email }}" class="text-decoration-none d-block">
                                        <i class="fas fa-envelope me-1 text-muted"></i>{{ $user->email }}
                                    </a>
                                    @if($user->phone)
                                        <small class="text-muted">
                                            <i class="fas fa-phone me-1"></i>{{ $user->phone }}
                                        </small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge bg-danger">
                                        <i class="fas fa-user-shield me-1"></i>ادمن
                                    </span>
                                @elseif($user->role === 'employee')
                                    <span class="badge bg-info">
                                        <i class="fas fa-user-tie me-1"></i>موظف
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-user me-1"></i>{{ $user->role }}
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success fs-6">
                                    <i class="fas fa-clock me-1"></i>{{ $user->time_entries_count }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning fs-6">
                                    <i class="fas fa-tasks me-1"></i>{{ $user->tasks_count }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info fs-6">
                                    <i class="fas fa-project-diagram me-1"></i>{{ $user->projects_count }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>{{ $user->created_at->format('Y-m-d') }}
                                </small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.users.show', $user) }}" 
                                       class="btn btn-sm btn-outline-info" 
                                       title="عرض التفاصيل"
                                       data-bs-toggle="tooltip">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}" 
                                       class="btn btn-sm btn-outline-warning" 
                                       title="تعديل"
                                       data-bs-toggle="tooltip">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form method="POST" 
                                              action="{{ route('admin.users.destroy', $user) }}" 
                                              class="d-inline" 
                                              onsubmit="return confirm('هل أنت متأكد من حذف الموظف {{ $user->name }}؟\n\nسيتم حذف جميع البيانات المرتبطة بهذا الموظف.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    title="حذف"
                                                    data-bs-toggle="tooltip">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    عرض {{ $users->firstItem() }} إلى {{ $users->lastItem() }} من أصل {{ $users->total() }} موظف
                </div>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        @else
            <div class="text-center text-muted py-5">
                <div class="mb-4">
                    <i class="fas fa-user-tie" style="font-size: 5rem; opacity: 0.3;"></i>
                </div>
                <h4 class="mb-3">لا يوجد موظفين حالياً</h4>
                <p class="mb-4">ابدأ بإضافة موظف جديد لإدارة المشاريع والمهام</p>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus me-2"></i>إضافة موظف جديد
                </a>
            </div>
        @endif
    </div>
</div>

<style>
    .table th {
        font-weight: 600;
        border-bottom: 2px solid #e2e8f0;
    }
    
    .table tbody tr {
        transition: all 0.2s ease;
    }
    
    .table tbody tr:hover {
        background-color: #f7fafc;
        transform: translateX(-2px);
    }
    
    .avatar {
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .card-header {
        border-bottom: 2px solid #e2e8f0;
    }
    
    .badge {
        padding: 0.5rem 0.75rem;
        font-weight: 500;
    }
</style>

<script>
    // تفعيل tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endsection
