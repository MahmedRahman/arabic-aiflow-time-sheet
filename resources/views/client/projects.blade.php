@extends('layouts.app')

@section('title', 'مشاريعي')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-project-diagram me-2"></i>مشاريعي</h2>
    <div class="text-muted">
        مرحباً {{ $client->name }}
    </div>
</div>

<!-- ملخص سريع للمشاريع -->
@if($projects->count() > 0)
<div class="row mb-4">
    @php
        $totalProjects = $projects->count();
        $activeProjects = $projects->where('status', 'active')->count();
        $completedProjects = $projects->where('status', 'completed')->count();
        $onHoldProjects = $projects->where('status', 'on_hold')->count();
    @endphp
    
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h4 class="card-title">{{ $totalProjects }}</h4>
                <p class="card-text">إجمالي المشاريع</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h4 class="card-title">{{ $activeProjects }}</h4>
                <p class="card-text">مشاريع نشطة</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h4 class="card-title">{{ $completedProjects }}</h4>
                <p class="card-text">مشاريع مكتملة</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h4 class="card-title">{{ $onHoldProjects }}</h4>
                <p class="card-text">مشاريع متوقفة</p>
            </div>
        </div>
    </div>
</div>
@endif

@if($projects->count() > 0)
    <div class="row">
        @foreach($projects as $project)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ $project->name }}</h5>
                    @if($project->status == 'active')
                        <span class="badge bg-success">نشط</span>
                    @elseif($project->status == 'completed')
                        <span class="badge bg-primary">مكتمل</span>
                    @else
                        <span class="badge bg-warning">متوقف</span>
                    @endif
                </div>
                <div class="card-body">
                    @if($project->description)
                        <p class="card-text">{{ Str::limit($project->description, 100) }}</p>
                    @endif
                    
                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <div class="border-end">
                                <h6 class="text-muted mb-1">سعر الساعة</h6>
                                <strong class="text-primary">{{ number_format($project->hourly_rate, 2) }} ج.م</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <h6 class="text-muted mb-1">إجمالي الساعات</h6>
                            <strong class="text-info">{{ $project->time_entries_sum_hours_worked ?? 0 }} ساعة</strong>
                        </div>
                    </div>
                    
                    <div class="row text-center">
                        <div class="col-12">
                            <h6 class="text-muted mb-1">إجمالي المبلغ</h6>
                            <strong class="text-success fs-5">{{ number_format($project->time_entries_sum_total_amount ?? 0, 2) }} ج.م</strong>
                        </div>
                    </div>
                    
                    @if($project->timeEntries->where('status', 'pending')->count() > 0)
                    <div class="mt-2">
                        <div class="alert alert-warning alert-sm mb-0">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            <small>{{ $project->timeEntries->where('status', 'pending')->count() }} سجل في انتظار موافقتك</small>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            @if($project->start_date)
                                بداية: {{ $project->start_date->format('Y-m-d') }}
                            @endif
                        </small>
                        <small class="text-muted">
                            {{ $project->time_entries_count ?? 0 }} سجل وقت
                        </small>
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('client.time-entries') }}" class="btn btn-outline-primary btn-sm w-100">
                            <i class="fas fa-clock me-1"></i>عرض سجلات الوقت
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@else
    <div class="text-center text-muted py-5">
        <i class="fas fa-project-diagram fa-3x mb-3"></i>
        <h4>لا توجد مشاريع حالياً</h4>
        <p>سيتم إضافة مشاريعك هنا عند إنشائها</p>
    </div>
@endif
@endsection
