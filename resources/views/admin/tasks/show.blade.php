@extends('layouts.app')

@section('title', 'تفاصيل المهمة')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-tasks me-2"></i>{{ $task->title }}</h2>
    <div>
        <a href="{{ route('admin.tasks.edit', $task) }}" class="btn btn-warning me-2">
            <i class="fas fa-edit me-2"></i>تعديل
        </a>
        <a href="{{ route('admin.tasks.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    معلومات المهمة
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>العنوان:</strong> {{ $task->title }}</p>
                        <p><strong>الوقت المتوقع:</strong> 
                            <span class="badge bg-info">{{ number_format($task->estimated_time, 2) }} ساعة</span>
                        </p>
                        <p><strong>الحالة:</strong> 
                            {!! $task->getStatusBadge() !!}
                        </p>
                    </div>
                    <div class="col-md-6">
                        @if($task->project)
                            <p><strong>المشروع:</strong> 
                                <a href="{{ route('admin.projects.show', $task->project) }}" class="text-decoration-none">
                                    {{ $task->project->name }}
                                </a>
                            </p>
                        @endif
                        @if($task->user)
                            <p><strong>المستخدم:</strong> {{ $task->user->name }}</p>
                        @endif
                        @if($task->due_date)
                            <p><strong>تاريخ الاستحقاق:</strong> {{ $task->due_date->format('Y-m-d') }}</p>
                        @endif
                    </div>
                </div>
                
                @if($task->description)
                    <div class="mt-3">
                        <strong>الوصف:</strong>
                        <p class="mt-2">{{ $task->description }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar me-2"></i>
                    معلومات إضافية
                </h5>
            </div>
            <div class="card-body">
                <p><strong>تاريخ الإنشاء:</strong> {{ $task->created_at->format('Y-m-d H:i') }}</p>
                <p><strong>آخر تحديث:</strong> {{ $task->updated_at->format('Y-m-d H:i') }}</p>
                
                @if($task->project)
                    <hr>
                    <h6 class="mb-3">معلومات المشروع</h6>
                    <p><strong>اسم المشروع:</strong> {{ $task->project->name }}</p>
                    <p><strong>العميل:</strong> {{ $task->project->client->name }}</p>
                    <a href="{{ route('admin.projects.show', $task->project) }}" class="btn btn-sm btn-outline-primary w-100">
                        <i class="fas fa-eye me-1"></i>عرض المشروع
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

