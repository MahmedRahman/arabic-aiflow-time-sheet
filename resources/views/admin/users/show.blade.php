@extends('layouts.app')

@section('title', 'تفاصيل الموظف')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-user me-2"></i>تفاصيل الموظف: {{ $user->name }}</h2>
    <div>
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning me-2">
            <i class="fas fa-edit me-2"></i>تعديل
        </a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="avatar bg-primary text-white rounded-circle mx-auto mb-3" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; font-size: 2.5rem;">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <h4>{{ $user->name }}</h4>
                <p class="text-muted mb-1">
                    <i class="fas fa-envelope me-1"></i>{{ $user->email }}
                </p>
                @if($user->phone)
                    <p class="text-muted mb-2">
                        <i class="fas fa-phone me-1"></i>{{ $user->phone }}
                    </p>
                @endif
                <div class="mt-3">
                    @if($user->role === 'admin')
                        <span class="badge bg-danger fs-6">
                            <i class="fas fa-user-shield me-1"></i>ادمن
                        </span>
                    @elseif($user->role === 'employee')
                        <span class="badge bg-info fs-6">
                            <i class="fas fa-user-tie me-1"></i>موظف
                        </span>
                    @else
                        <span class="badge bg-secondary fs-6">{{ $user->role }}</span>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">معلومات الحساب</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <strong><i class="fas fa-envelope me-2"></i>البريد الإلكتروني:</strong>
                        <br><a href="mailto:{{ $user->email }}" class="text-decoration-none">{{ $user->email }}</a>
                    </li>
                    @if($user->phone)
                        <li class="mb-2">
                            <strong><i class="fas fa-phone me-2"></i>رقم التليفون:</strong>
                            <br><a href="tel:{{ $user->phone }}" class="text-decoration-none">{{ $user->phone }}</a>
                        </li>
                    @endif
                    <li class="mb-2">
                        <strong><i class="fas fa-calendar me-2"></i>تاريخ الإنشاء:</strong>
                        <br>{{ $user->created_at->format('Y-m-d H:i') }}
                    </li>
                    <li class="mb-2">
                        <strong><i class="fas fa-clock me-2"></i>آخر تحديث:</strong>
                        <br>{{ $user->updated_at->format('Y-m-d H:i') }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">إحصائيات</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary text-white rounded-circle p-3 me-3">
                                <i class="fas fa-clock fa-lg"></i>
                            </div>
                            <div>
                                <h4 class="mb-0">{{ $user->timeEntries()->count() }}</h4>
                                <p class="text-muted mb-0">سجلات الوقت</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-success text-white rounded-circle p-3 me-3">
                                <i class="fas fa-tasks fa-lg"></i>
                            </div>
                            <div>
                                <h4 class="mb-0">{{ $user->tasks()->count() }}</h4>
                                <p class="text-muted mb-0">المهام</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">سجلات الوقت الأخيرة</h5>
            </div>
            <div class="card-body">
                @if($user->timeEntries->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>التاريخ</th>
                                    <th>المشروع</th>
                                    <th>الساعات</th>
                                    <th>المبلغ</th>
                                    <th>الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->timeEntries()->latest()->limit(10)->get() as $entry)
                                <tr>
                                    <td>{{ $entry->date->format('Y-m-d') }}</td>
                                    <td>{{ $entry->project->name ?? 'غير محدد' }}</td>
                                    <td>{{ $entry->hours_worked }} ساعة</td>
                                    <td>{{ number_format($entry->total_amount, 2) }} ج.م</td>
                                    <td>
                                        @if($entry->status == 'approved')
                                            <span class="badge bg-success">موافق عليه</span>
                                        @elseif($entry->status == 'rejected')
                                            <span class="badge bg-danger">مرفوض</span>
                                        @else
                                            <span class="badge bg-warning">في الانتظار</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <p>لا توجد سجلات وقت لهذا الموظف</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

