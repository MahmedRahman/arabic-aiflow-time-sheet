@extends('layouts.app')

@section('title', 'تفاصيل المشروع')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-project-diagram me-2"></i>{{ $project->name }}</h2>
    <div>
        <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-warning me-2">
            <i class="fas fa-edit me-2"></i>تعديل
        </a>
        <a href="{{ route('admin.projects.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
        </a>
    </div>
</div>

<div class="row">
    <!-- معلومات المشروع -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    معلومات المشروع
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>اسم المشروع:</strong> {{ $project->name }}</p>
                        <p><strong>العميل:</strong> {{ $project->client->name }}</p>
                        <p><strong>البريد الإلكتروني:</strong> {{ $project->client->email }}</p>
                        @if($project->client->phone)
                            <p><strong>الهاتف:</strong> {{ $project->client->phone }}</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <p><strong>سعر الساعة:</strong> {{ number_format($project->hourly_rate, 2) }} ج.م</p>
                        <p><strong>الحالة:</strong> 
                            @if($project->status == 'active')
                                <span class="badge bg-success">نشط</span>
                            @elseif($project->status == 'completed')
                                <span class="badge bg-primary">مكتمل</span>
                            @else
                                <span class="badge bg-warning">متوقف</span>
                            @endif
                        </p>
                        @if($project->start_date)
                            <p><strong>تاريخ البداية:</strong> {{ $project->start_date->format('Y-m-d') }}</p>
                        @endif
                        @if($project->end_date)
                            <p><strong>تاريخ النهاية:</strong> {{ $project->end_date->format('Y-m-d') }}</p>
                        @endif
                    </div>
                </div>
                
                @if($project->description)
                    <div class="mt-3">
                        <strong>الوصف:</strong>
                        <p class="mt-2">{{ $project->description }}</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- سجلات الوقت -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clock me-2"></i>
                    سجلات الوقت
                </h5>
                <a href="{{ route('admin.time-entries.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i>إضافة سجل وقت
                </a>
            </div>
            <div class="card-body">
                @if($project->timeEntries->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>التاريخ</th>
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
                                @foreach($project->timeEntries as $entry)
                                <tr>
                                    <td>{{ $entry->date->format('Y-m-d') }}</td>
                                    <td>{{ $entry->user->name }}</td>
                                    <td>{{ $entry->start_time }}</td>
                                    <td>{{ $entry->end_time }}</td>
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
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-clock fa-2x mb-3"></i>
                        <p>لا توجد سجلات وقت لهذا المشروع</p>
                        <a href="{{ route('admin.time-entries.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>إضافة سجل وقت
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- إحصائيات المشروع -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    إحصائيات المشروع
                </h5>
            </div>
            <div class="card-body">
                @php
                    $totalHours = $project->timeEntries->sum('hours_worked');
                    $totalAmount = $project->timeEntries->sum('total_amount');
                    $approvedAmount = $project->timeEntries->where('status', 'approved')->sum('total_amount');
                    $pendingAmount = $project->timeEntries->where('status', 'pending')->sum('total_amount');
                @endphp
                
                <div class="row text-center mb-3">
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-primary mb-1">{{ number_format($totalHours, 2) }}</h4>
                            <small class="text-muted">إجمالي الساعات</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success mb-1">{{ number_format($totalAmount, 2) }}</h4>
                        <small class="text-muted">إجمالي المبلغ</small>
                    </div>
                </div>
                
                <div class="row text-center mb-3">
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-success mb-1">{{ number_format($approvedAmount, 2) }}</h4>
                            <small class="text-muted">موافق عليه</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-warning mb-1">{{ number_format($pendingAmount, 2) }}</h4>
                        <small class="text-muted">في الانتظار</small>
                    </div>
                </div>
                
                <div class="row text-center">
                    <div class="col-12">
                        <h4 class="text-info mb-1">{{ $project->timeEntries->count() }}</h4>
                        <small class="text-muted">عدد سجلات الوقت</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- معلومات العميل -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user me-2"></i>
                    معلومات العميل
                </h5>
            </div>
            <div class="card-body">
                <p><strong>الاسم:</strong> {{ $project->client->name }}</p>
                <p><strong>البريد الإلكتروني:</strong> {{ $project->client->email }}</p>
                @if($project->client->phone)
                    <p><strong>الهاتف:</strong> {{ $project->client->phone }}</p>
                @endif
                @if($project->client->company)
                    <p><strong>الشركة:</strong> {{ $project->client->company }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
