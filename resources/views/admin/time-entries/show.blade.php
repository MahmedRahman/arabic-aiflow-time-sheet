@extends('layouts.app')

@section('title', 'تفاصيل سجل الوقت')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-clock me-2"></i>تفاصيل سجل الوقت</h2>
    <div>
        <a href="{{ route('admin.time-entries.edit', $timeEntry) }}" class="btn btn-warning me-2">
            <i class="fas fa-edit me-2"></i>تعديل
        </a>
        <a href="{{ route('admin.time-entries.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
        </a>
    </div>
</div>

<div class="row">
    <!-- تفاصيل سجل الوقت -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    تفاصيل سجل الوقت
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>التاريخ:</strong> {{ $timeEntry->date->format('Y-m-d') }}</p>
                        <p><strong>وقت البداية:</strong> 
                            <span class="badge bg-light text-dark fs-6">{{ $timeEntry->start_time }}</span>
                        </p>
                        <p><strong>وقت النهاية:</strong> 
                            <span class="badge bg-light text-dark fs-6">{{ $timeEntry->end_time }}</span>
                        </p>
                        <p><strong>الساعات:</strong> 
                            <span class="badge bg-info">{{ $timeEntry->hours_worked }} ساعة</span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>المشروع:</strong> {{ $timeEntry->project->name }}</p>
                        @if($timeEntry->task)
                            <p><strong>المهمة:</strong> 
                                <a href="{{ route('admin.tasks.show', $timeEntry->task) }}" class="text-decoration-none">
                                    <i class="fas fa-tasks me-1"></i>{{ $timeEntry->task->title }}
                                </a>
                            </p>
                        @endif
                        <p><strong>العميل:</strong> {{ $timeEntry->project->client->name }}</p>
                        <p><strong>المطور:</strong> {{ $timeEntry->user->name }}</p>
                        <p><strong>سعر الساعة:</strong> {{ number_format($timeEntry->hourly_rate, 2) }} ج.م</p>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-6">
                        <p><strong>المبلغ الإجمالي:</strong> 
                            <span class="text-success fs-5">{{ number_format($timeEntry->total_amount, 2) }} ج.م</span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>الحالة:</strong> 
                            @if($timeEntry->status == 'approved')
                                <span class="badge bg-success">موافق عليه</span>
                            @elseif($timeEntry->status == 'rejected')
                                <span class="badge bg-danger">مرفوض</span>
                            @else
                                <span class="badge bg-warning">في الانتظار</span>
                            @endif
                        </p>
                    </div>
                </div>
                
                @if($timeEntry->description)
                    <div class="mt-3">
                        <strong>وصف العمل:</strong>
                        <p class="mt-2 bg-light p-3 rounded">{{ $timeEntry->description }}</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- إجراءات الموافقة -->
        @if($timeEntry->status == 'pending')
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-check-circle me-2"></i>
                    إجراءات الموافقة
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex gap-2">
                    <form method="POST" action="{{ route('admin.time-entries.approve', $timeEntry) }}" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success" 
                                onclick="return confirm('هل أنت متأكد من الموافقة على هذا السجل؟')">
                            <i class="fas fa-check me-2"></i>موافقة
                        </button>
                    </form>
                    
                    <form method="POST" action="{{ route('admin.time-entries.reject', $timeEntry) }}" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger" 
                                onclick="return confirm('هل أنت متأكد من رفض هذا السجل؟')">
                            <i class="fas fa-times me-2"></i>رفض
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <!-- معلومات إضافية -->
    <div class="col-md-4">
        <!-- معلومات المشروع -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-project-diagram me-2"></i>
                    معلومات المشروع
                </h5>
            </div>
            <div class="card-body">
                <p><strong>اسم المشروع:</strong> {{ $timeEntry->project->name }}</p>
                <p><strong>حالة المشروع:</strong> 
                    @if($timeEntry->project->status == 'active')
                        <span class="badge bg-success">نشط</span>
                    @elseif($timeEntry->project->status == 'completed')
                        <span class="badge bg-primary">مكتمل</span>
                    @else
                        <span class="badge bg-warning">متوقف</span>
                    @endif
                </p>
                @if($timeEntry->project->description)
                    <p><strong>وصف المشروع:</strong></p>
                    <p class="text-muted small">{{ Str::limit($timeEntry->project->description, 100) }}</p>
                @endif
            </div>
        </div>
        
        <!-- معلومات العميل -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user me-2"></i>
                    معلومات العميل
                </h5>
            </div>
            <div class="card-body">
                <p><strong>الاسم:</strong> {{ $timeEntry->project->client->name }}</p>
                <p><strong>البريد الإلكتروني:</strong> {{ $timeEntry->project->client->email }}</p>
                @if($timeEntry->project->client->phone)
                    <p><strong>الهاتف:</strong> {{ $timeEntry->project->client->phone }}</p>
                @endif
                @if($timeEntry->project->client->company)
                    <p><strong>الشركة:</strong> {{ $timeEntry->project->client->company }}</p>
                @endif
            </div>
        </div>
        
        <!-- معلومات المطور -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-tie me-2"></i>
                    معلومات المطور
                </h5>
            </div>
            <div class="card-body">
                <p><strong>الاسم:</strong> {{ $timeEntry->user->name }}</p>
                <p><strong>البريد الإلكتروني:</strong> {{ $timeEntry->user->email }}</p>
                <p><strong>نوع الحساب:</strong> 
                    @if($timeEntry->user->isAdmin())
                        <span class="badge bg-primary">مدير</span>
                    @else
                        <span class="badge bg-info">عميل</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
