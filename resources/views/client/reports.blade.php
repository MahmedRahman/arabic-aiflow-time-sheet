@extends('layouts.app')

@section('title', 'التقارير')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-chart-bar me-2"></i>التقارير</h2>
    <div class="text-muted">
        مرحباً {{ $client->name }}
    </div>
</div>

<!-- ملخص سريع -->
<div class="row mb-4">
    @php
        $totalHours = $project_stats->sum('time_entries_sum_hours_worked');
        $totalAmount = $project_stats->sum('time_entries_sum_total_amount');
        $totalEntries = $project_stats->sum('time_entries_count');
        $pendingEntries = $project_stats->sum(function($project) {
            return $project->timeEntries->where('status', 'pending')->count();
        });
        $approvedEntries = $project_stats->sum(function($project) {
            return $project->timeEntries->where('status', 'approved')->count();
        });
    @endphp
    
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h4 class="card-title">{{ number_format($totalHours, 2) }}</h4>
                <p class="card-text">إجمالي الساعات</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h4 class="card-title">{{ number_format($totalAmount, 2) }} ج.م</h4>
                <p class="card-text">إجمالي المبلغ</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h4 class="card-title">{{ $pendingEntries }}</h4>
                <p class="card-text">في انتظار الموافقة</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h4 class="card-title">{{ $approvedEntries }}</h4>
                <p class="card-text">موافق عليها</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- إحصائيات المشاريع -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-project-diagram me-2"></i>
                    إحصائيات المشاريع
                </h5>
            </div>
            <div class="card-body">
                @if($project_stats->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>اسم المشروع</th>
                                    <th>عدد سجلات الوقت</th>
                                    <th>إجمالي الساعات</th>
                                    <th>إجمالي المبلغ</th>
                                    <th>متوسط الساعات/السجل</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($project_stats as $project)
                                <tr>
                                    <td>
                                        <strong>{{ $project->name }}</strong>
                                        @if($project->description)
                                            <br><small class="text-muted">{{ Str::limit($project->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $project->time_entries_count }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ number_format($project->time_entries_sum_hours_worked, 2) }} ساعة</span>
                                    </td>
                                    <td>
                                        <strong class="text-success">{{ number_format($project->time_entries_sum_total_amount, 2) }} ج.م</strong>
                                    </td>
                                    <td>
                                        @if($project->time_entries_count > 0)
                                            {{ number_format($project->time_entries_sum_hours_worked / $project->time_entries_count, 2) }} ساعة
                                        @else
                                            0 ساعة
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-project-diagram fa-2x mb-3"></i>
                        <p>لا توجد مشاريع حالياً</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- الإحصائيات الشهرية -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>
                    الإحصائيات الشهرية {{ date('Y') }}
                </h5>
            </div>
            <div class="card-body">
                @if($monthly_data->count() > 0)
                    @foreach($monthly_data as $data)
                    <div class="d-flex justify-content-between align-items-center mb-3 p-2 border rounded">
                        <div>
                            <h6 class="mb-0">شهر {{ $data->month }}</h6>
                            <small class="text-muted">{{ number_format($data->total_hours, 2) }} ساعة</small>
                        </div>
                        <div class="text-end">
                            <strong class="text-success">{{ number_format($data->total_amount, 2) }} ج.م</strong>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-chart-line fa-2x mb-2"></i>
                        <p>لا توجد بيانات شهرية</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- ملخص سريع -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    ملخص سريع
                </h5>
            </div>
            <div class="card-body">
                @php
                    $totalHours = $project_stats->sum('time_entries_sum_hours_worked');
                    $totalAmount = $project_stats->sum('time_entries_sum_total_amount');
                    $totalEntries = $project_stats->sum('time_entries_count');
                @endphp
                
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="border-end">
                            <h4 class="text-primary mb-1">{{ number_format($totalHours, 2) }}</h4>
                            <small class="text-muted">إجمالي الساعات</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <h4 class="text-success mb-1">{{ number_format($totalAmount, 2) }}</h4>
                        <small class="text-muted">إجمالي المبلغ</small>
                    </div>
                </div>
                
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-info mb-1">{{ $totalEntries }}</h4>
                        <small class="text-muted">إجمالي السجلات</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-warning mb-1">
                            @if($totalEntries > 0)
                                {{ number_format($totalAmount / $totalEntries, 2) }}
                            @else
                                0
                            @endif
                        </h4>
                        <small class="text-muted">متوسط/السجل</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
