@extends('layouts.app')

@section('title', 'تفاصيل العميل')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-user me-2"></i>{{ $client->name }}</h2>
    <div>
        <a href="{{ route('admin.clients.edit', $client) }}" class="btn btn-warning me-2">
            <i class="fas fa-edit me-2"></i>تعديل
        </a>
        <a href="{{ route('admin.clients.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
        </a>
    </div>
</div>

<div class="row">
    <!-- معلومات العميل -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    معلومات العميل
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>الاسم:</strong> {{ $client->name }}</p>
                        <p><strong>البريد الإلكتروني:</strong> {{ $client->email }}</p>
                        <p><strong>الهاتف:</strong> {{ $client->phone ?? 'غير محدد' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>الشركة:</strong> {{ $client->company ?? 'غير محدد' }}</p>
                        <p><strong>تاريخ الإنشاء:</strong> {{ $client->created_at->format('Y-m-d') }}</p>
                        <p><strong>آخر تحديث:</strong> {{ $client->updated_at->format('Y-m-d') }}</p>
                    </div>
                </div>
                
                @if($client->address)
                    <div class="mt-3">
                        <strong>العنوان:</strong>
                        <p class="mt-2">{{ $client->address }}</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- مشاريع العميل -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-project-diagram me-2"></i>
                    مشاريع العميل
                </h5>
                <a href="{{ route('admin.projects.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i>إضافة مشروع
                </a>
            </div>
            <div class="card-body">
                @if($client->projects->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>اسم المشروع</th>
                                    <th>سعر الساعة</th>
                                    <th>الحالة</th>
                                    <th>عدد سجلات الوقت</th>
                                    <th>إجمالي الساعات</th>
                                    <th>إجمالي المبلغ</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($client->projects as $project)
                                <tr>
                                    <td>
                                        <strong>{{ $project->name }}</strong>
                                        @if($project->description)
                                            <br><small class="text-muted">{{ Str::limit($project->description, 50) }}</small>
                                        @endif
                                    </td>
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
                                        <span class="badge bg-info">{{ $project->timeEntries->count() }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ number_format($project->timeEntries->sum('hours_worked'), 2) }} ساعة</span>
                                    </td>
                                    <td>
                                        <strong>{{ number_format($project->timeEntries->sum('total_amount'), 2) }} ج.م</strong>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.projects.show', $project) }}" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-sm btn-outline-warning">
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
                        <i class="fas fa-project-diagram fa-2x mb-3"></i>
                        <p>لا توجد مشاريع لهذا العميل</p>
                        <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>إضافة مشروع
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- إحصائيات العميل -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    إحصائيات العميل
                </h5>
            </div>
            <div class="card-body">
                @php
                    $totalProjects = $client->projects->count();
                    $totalTimeEntries = $client->projects->sum(function($project) {
                        return $project->timeEntries->count();
                    });
                    $totalHours = $client->projects->sum(function($project) {
                        return $project->timeEntries->sum('hours_worked');
                    });
                    $totalAmount = $client->projects->sum(function($project) {
                        return $project->timeEntries->sum('total_amount');
                    });
                    $approvedAmount = $client->projects->sum(function($project) {
                        return $project->timeEntries->where('status', 'approved')->sum('total_amount');
                    });
                    $pendingAmount = $client->projects->sum(function($project) {
                        return $project->timeEntries->where('status', 'pending')->sum('total_amount');
                    });
                @endphp
                
                <div class="row text-center mb-3">
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-primary mb-1">{{ $totalProjects }}</h4>
                            <small class="text-muted">عدد المشاريع</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-info mb-1">{{ $totalTimeEntries }}</h4>
                        <small class="text-muted">سجلات الوقت</small>
                    </div>
                </div>
                
                <div class="row text-center mb-3">
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-success mb-1">{{ number_format($totalHours, 2) }}</h4>
                            <small class="text-muted">إجمالي الساعات</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-warning mb-1">{{ number_format($totalAmount, 2) }}</h4>
                        <small class="text-muted">إجمالي المبلغ</small>
                    </div>
                </div>
                
                <div class="row text-center">
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
            </div>
        </div>
        
        <!-- معلومات إضافية -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    معلومات إضافية
                </h5>
            </div>
            <div class="card-body">
                <p><strong>تاريخ الإنشاء:</strong> {{ $client->created_at->format('Y-m-d H:i') }}</p>
                <p><strong>آخر تحديث:</strong> {{ $client->updated_at->format('Y-m-d H:i') }}</p>
                <p><strong>حالة الحساب:</strong> 
                    <span class="badge bg-success">نشط</span>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
