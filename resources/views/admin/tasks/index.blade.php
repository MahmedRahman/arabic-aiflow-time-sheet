@extends('layouts.app')

@section('title', 'إدارة المهام')

@section('content')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'نجح!',
        text: '{{ session('success') }}',
        confirmButtonText: 'حسناً',
        confirmButtonColor: '#28a745'
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'خطأ!',
        text: '{{ session('error') }}',
        confirmButtonText: 'حسناً',
        confirmButtonColor: '#dc3545'
    });
</script>
@endif

@php
    // دالة لتحويل الساعات إلى ساعات ودقائق
    function formatHoursToTime($hours) {
        if ($hours == 0) {
            return '0 دقيقة';
        }
        
        $totalMinutes = round($hours * 60);
        $hoursPart = floor($totalMinutes / 60);
        $minutesPart = $totalMinutes % 60;
        
        if ($hoursPart > 0 && $minutesPart > 0) {
            return $hoursPart . ' ساعة و ' . $minutesPart . ' دقيقة';
        } elseif ($hoursPart > 0) {
            return $hoursPart . ' ساعة';
        } else {
            return $minutesPart . ' دقيقة';
        }
    }
@endphp

<style>
    /* تحسينات RTL للكروت - تصميم موحد وهادئ */
    .task-card {
        transition: all 0.2s ease;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        overflow: hidden;
        background: #ffffff;
        direction: rtl;
        text-align: right;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .task-card:hover {
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        border-color: #cbd5e0;
    }
    
    .task-card-header {
        background: #f7fafc;
        color: #2d3748;
        border-bottom: 1px solid #e2e8f0;
        padding: 1rem 1.25rem;
        direction: rtl;
    }
    
    .task-card-header h5 {
        color: #2d3748;
        font-weight: 600;
        font-size: 1rem;
        direction: rtl;
        text-align: right;
    }
    
    .task-card-header h5 i {
        margin-left: 0.5rem;
        margin-right: 0;
        color: #4a5568;
    }
    
    .task-card-body {
        padding: 1.25rem;
        direction: rtl;
        text-align: right;
    }
    
    .time-info-box {
        background: #f7fafc;
        border: 1px solid #e2e8f0;
        padding: 0.75rem;
        border-radius: 6px;
        margin-bottom: 0.75rem;
        direction: rtl;
        text-align: right;
    }
    
    .time-info-box.success {
        background: #ebf8ff;
        border-color: #bee3f8;
    }
    
    .time-info-box .d-flex {
        direction: rtl;
    }
    
    .time-info-box i {
        margin-left: 0.5rem;
        margin-right: 0;
        color: #718096;
    }
    
    .info-item {
        padding: 0.5rem 0.75rem;
        background: #f7fafc;
        border-radius: 6px;
        margin-bottom: 0.5rem;
        border: 1px solid #e2e8f0;
        direction: rtl;
        text-align: right;
    }
    
    .info-item .d-flex {
        direction: rtl;
    }
    
    .info-item i {
        width: 20px;
        text-align: center;
        color: #718096;
        margin-left: 0.5rem;
        margin-right: 0;
        flex-shrink: 0;
    }
    
    .sessions-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        direction: rtl;
        text-align: right;
    }
    
    .session-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1rem;
        transition: all 0.2s ease;
        direction: rtl;
        text-align: right;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        position: relative;
        overflow: hidden;
    }
    
    .session-card::before {
        content: '';
        position: absolute;
        right: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: #48bb78;
        transition: all 0.2s ease;
    }
    
    .session-card.unpaid::before {
        background: #ed8936;
    }
    
    .session-card:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border-color: #cbd5e0;
        transform: translateY(-1px);
    }
    
    .session-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .session-date-time {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    
    .session-date {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #2d3748;
        font-weight: 600;
        font-size: 0.95rem;
    }
    
    .session-date i {
        color: #4a5568;
        font-size: 0.9rem;
    }
    
    .session-time-range {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #718096;
        font-size: 0.9rem;
    }
    
    .session-time-range i {
        color: #a0aec0;
        font-size: 0.85rem;
    }
    
    .session-status-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .session-status-badge.paid {
        background: #c6f6d5;
        color: #22543d;
    }
    
    .session-status-badge.unpaid {
        background: #feebc8;
        color: #7c2d12;
    }
    
    .session-status-badge:hover {
        opacity: 0.85;
        transform: scale(1.02);
    }
    
    .session-body {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }
    
    .session-info {
        flex: 1;
    }
    
    .session-description {
        color: #718096;
        font-size: 0.9rem;
        margin-top: 0.5rem;
        line-height: 1.5;
    }
    
    .session-actions {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .session-duration {
        background: #f0fff4;
        border: 1px solid #9ae6b4;
        border-radius: 6px;
        padding: 0.5rem 0.75rem;
        color: #22543d;
        font-weight: 600;
        font-size: 0.95rem;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    
    .session-duration i {
        color: #48bb78;
        font-size: 0.9rem;
    }
    
    .session-buttons {
        display: flex;
        gap: 0.5rem;
    }
    
    .session-btn {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        border: 1px solid;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    
    .session-btn-edit {
        background: #fffbf0;
        border-color: #fbd38d;
        color: #c05621;
    }
    
    .session-btn-edit:hover {
        background: #feebc8;
        border-color: #f6ad55;
        color: #9c4221;
        transform: translateY(-1px);
    }
    
    .session-btn-delete {
        background: #fff5f5;
        border-color: #fc8181;
        color: #c53030;
    }
    
    .session-btn-delete:hover {
        background: #fed7d7;
        border-color: #f56565;
        color: #9b2c2c;
        transform: translateY(-1px);
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .session-card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }
        
        .session-date-time {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
        
        .session-body {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }
        
        .session-actions {
            width: 100%;
            justify-content: space-between;
        }
        
        .session-duration {
            font-size: 0.85rem;
            padding: 0.4rem 0.6rem;
        }
    }
    
    .sessions-list::-webkit-scrollbar {
        width: 6px;
    }
    
    .sessions-list::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }
    
    .sessions-list::-webkit-scrollbar-thumb {
        background: #cbd5e0;
        border-radius: 3px;
    }
    
    .sessions-list::-webkit-scrollbar-thumb:hover {
        background: #a0aec0;
    }
    
    .tracking-button {
        border-radius: 6px;
        padding: 0.6rem;
        font-weight: 500;
        transition: all 0.2s ease;
        border: 1px solid;
        direction: rtl;
    }
    
    .tracking-button i {
        margin-left: 0.5rem;
        margin-right: 0;
    }
    
    .tracking-button:hover {
        opacity: 0.9;
    }
    
    .tracking-button.start {
        background: #48bb78;
        border-color: #48bb78;
        color: white;
    }
    
    .tracking-button.stop {
        background: #f56565;
        border-color: #f56565;
        color: white;
    }
    
    .card-footer {
        background: #f7fafc;
        border-top: 1px solid #e2e8f0;
        padding: 0.75rem;
        direction: rtl;
    }
    
    .card-footer .btn-group {
        direction: rtl;
    }
    
    .card-footer .btn i {
        margin: 0;
    }
    
    .timer-display {
        font-size: 1.1rem;
        font-weight: 600;
        color: #f56565;
        padding: 0.75rem;
        background: #fff5f5;
        border: 1px solid #fed7d7;
        border-radius: 6px;
        font-family: 'Courier New', monospace;
        direction: ltr;
        text-align: center;
    }
    
    .timer-display i {
        margin-left: 0.5rem;
        margin-right: 0;
    }
    
    .alert {
        direction: rtl;
        text-align: right;
    }
    
    .alert i {
        margin-left: 0.5rem;
        margin-right: 0;
    }
    
    .text-muted {
        color: #718096 !important;
    }
    
    .badge {
        direction: rtl;
    }
    
    .toggle-paid-btn {
        font-size: 0.85rem;
        padding: 0.4rem 0.75rem;
    }
</style>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-tasks me-2"></i>إدارة المهام</h2>
    <a href="{{ route('admin.tasks.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>إضافة مهمة جديدة
    </a>
</div>

@if($tasks->count() > 0)
    <div class="row">
        @foreach($tasks as $task)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card task-card h-100 shadow-lg" id="task-card-{{ $task->id }}" data-task-id="{{ $task->id }}">
                <div class="card-header task-card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-tasks"></i>{{ Str::limit($task->title, 30) }}
                    </h5>
                    <div>{!! $task->getStatusBadge() !!}</div>
                </div>
                <div class="card-body task-card-body">
                    @if($task->description)
                        <p class="card-text text-muted mb-3" style="line-height: 1.6;">{{ Str::limit($task->description, 120) }}</p>
                    @endif
                    
                    <!-- معلومات الوقت -->
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="time-info-box">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-clock text-muted"></i>
                                    <div class="flex-grow-1">
                                        <small class="text-muted d-block">الوقت المتوقع</small>
                                        <strong class="text-dark">{{ formatHoursToTime($task->estimated_time ?? 0) }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="time-info-box success">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-hourglass-half text-muted"></i>
                                    <div class="flex-grow-1">
                                        <small class="text-muted d-block">إجمالي الوقت</small>
                                        <strong class="text-dark" id="total-time-{{ $task->id }}">{{ formatHoursToTime($task->total_time ?? 0) }}</strong>
                                        @if($task->total_sessions > 0)
                                            <small class="text-muted d-block mt-1">({{ $task->total_sessions }} جلسة)</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @php
                        $hasActiveSession = $task->active_session && $task->active_session->user_id == Auth::id();
                    @endphp
                    
                    @if($hasActiveSession)
                        <div class="alert alert-info mb-3 py-2">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-play-circle"></i>
                                <div class="flex-grow-1">
                                    <small class="text-muted d-block">بدأ التتبع في:</small>
                                    <strong>{{ $task->active_session->started_at->format('Y-m-d') }}</strong>
                                    <span class="text-muted"> في </span>
                                    <strong>{{ $task->active_session->started_at->format('H:i') }}</strong>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- معلومات إضافية -->
                    <div class="mb-3">
                        @if($task->project)
                            <div class="info-item d-flex align-items-center">
                                <i class="fas fa-project-diagram"></i>
                                <a href="{{ route('admin.projects.show', $task->project) }}" class="text-decoration-none text-dark flex-grow-1">
                                    {{ $task->project->name }}
                                </a>
                            </div>
                        @else
                            <div class="info-item d-flex align-items-center">
                                <i class="fas fa-project-diagram"></i>
                                <span class="text-muted">لا يوجد مشروع</span>
                            </div>
                        @endif
                        
                        @if($task->user)
                            <div class="info-item d-flex align-items-center">
                                <i class="fas fa-user"></i>
                                <span class="text-dark">{{ $task->user->name }}</span>
                            </div>
                        @endif
                        
                        @if($task->due_date)
                            <div class="info-item d-flex align-items-center">
                                <i class="fas fa-calendar-alt"></i>
                                <span class="text-dark">تاريخ الاستحقاق: <strong>{{ $task->due_date->format('Y-m-d') }}</strong></span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- تفاصيل الجلسات -->
                    @if(isset($task->completed_sessions) && $task->completed_sessions->count() > 0)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong class="text-dark">
                                    <i class="fas fa-history"></i> سجل الجلسات
                                </strong>
                                <span class="badge bg-secondary">{{ $task->completed_sessions->count() }} جلسة</span>
                            </div>
                            <div class="sessions-list">
                                @foreach($task->completed_sessions as $index => $session)
                                    <div class="session-card {{ $session->is_paid ? 'paid' : 'unpaid' }}">
                                        <div class="session-card-header">
                                            <div class="session-date-time">
                                                <div class="session-date">
                                                    <i class="fas fa-calendar-alt"></i>
                                                    <span>{{ $session->date->format('Y-m-d') }}</span>
                                                </div>
                                                <div class="session-time-range">
                                                    <i class="fas fa-clock"></i>
                                                    <span>
                                                        {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} - 
                                                        @if($session->end_time)
                                                            {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}
                                                        @else
                                                            جاري...
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                            <button type="button" 
                                                    class="session-status-badge toggle-paid-btn {{ $session->is_paid ? 'paid' : 'unpaid' }}"
                                                    data-time-entry-id="{{ $session->id }}"
                                                    data-is-paid="{{ $session->is_paid ? '1' : '0' }}">
                                                @if($session->is_paid)
                                                    <i class="fas fa-check-circle"></i>
                                                    <span>مدفوعة</span>
                                                @else
                                                    <i class="fas fa-clock"></i>
                                                    <span>غير مدفوعة</span>
                                                @endif
                                            </button>
                                        </div>
                                        <div class="session-body">
                                            <div class="session-info">
                                                @if($session->description)
                                                    <div class="session-description">
                                                        <i class="fas fa-align-right" style="margin-left: 0.5rem; color: #a0aec0;"></i>
                                                        {{ Str::limit($session->description, 60) }}
                                                    </div>
                                                @else
                                                    <div class="session-description" style="color: #cbd5e0; font-style: italic;">
                                                        لا يوجد وصف
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="session-actions">
                                                <div class="session-duration">
                                                    <i class="fas fa-hourglass-half"></i>
                                                    <span>{{ formatHoursToTime($session->hours_worked) }}</span>
                                                </div>
                                                <div class="session-buttons">
                                                    <a href="{{ route('admin.time-entries.edit', $session) }}" 
                                                       class="session-btn session-btn-edit" 
                                                       title="تعديل الجلسة">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="session-btn session-btn-delete delete-session-btn" 
                                                            data-time-entry-id="{{ $session->id }}"
                                                            title="حذف الجلسة">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <!-- تتبع الوقت -->
                    <div class="mb-3" id="tracking-buttons-{{ $task->id }}">
                        @if($task->project_id)
                            @if($hasActiveSession)
                                <button type="button" class="btn tracking-button stop stop-tracking w-100 mb-2 text-white" data-task-id="{{ $task->id }}">
                                    <i class="fas fa-stop"></i>إيقاف التتبع
                                </button>
                                <div class="text-center mt-2">
                                    <div class="timer-display" id="timer-{{ $task->id }}" data-started-at="{{ $task->active_session->started_at->timestamp }}">
                                        <i class="fas fa-clock"></i>
                                        <span id="timer-text-{{ $task->id }}" style="font-size: 1.3rem; letter-spacing: 2px; font-family: 'Courier New', monospace;">00:00:00</span>
                                    </div>
                                </div>
                            @else
                                <button type="button" class="btn tracking-button start start-tracking w-100 text-white" data-task-id="{{ $task->id }}">
                                    <i class="fas fa-play"></i>بدء التتبع
                                </button>
                            @endif
                        @else
                            <div class="alert alert-warning mb-0 py-2 rounded">
                                <small><i class="fas fa-exclamation-triangle"></i> يجب ربط المهمة بمشروع</small>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-footer">
                    <div class="btn-group w-100" role="group">
                        <a href="{{ route('admin.tasks.show', $task) }}" class="btn btn-sm btn-outline-info rounded-start">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.tasks.edit', $task) }}" class="btn btn-sm btn-outline-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.tasks.destroy', $task) }}" class="d-inline flex-grow-1 delete-task-form" data-task-id="{{ $task->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-sm btn-outline-danger w-100 rounded-end delete-task-btn" data-task-id="{{ $task->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="d-flex justify-content-center mt-4">
        {{ $tasks->links() }}
    </div>
@else
    <div class="card">
        <div class="card-body">
            <div class="text-center text-muted py-5">
                <i class="fas fa-tasks fa-3x mb-3"></i>
                <h4>لا توجد مهام حالياً</h4>
                <p>ابدأ بإضافة مهمة جديدة</p>
                <a href="{{ route('admin.tasks.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>إضافة مهمة جديدة
                </a>
            </div>
        </div>
    </div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    if (!csrfToken) {
        console.error('CSRF token not found!');
    }
    
    // معالجة بدء التتبع - استخدام event delegation
    document.addEventListener('click', function(e) {
        if (e.target.closest('.start-tracking')) {
            const button = e.target.closest('.start-tracking');
            const taskId = button.getAttribute('data-task-id');
            if (taskId) {
                console.log('Starting tracking for task:', taskId);
                startTracking(taskId);
            }
        }
        
        if (e.target.closest('.stop-tracking')) {
            const button = e.target.closest('.stop-tracking');
            const taskId = button.getAttribute('data-task-id');
            if (taskId) {
                console.log('Stopping tracking for task:', taskId);
                stopTracking(taskId);
            }
        }
    });
    
    // معالجة بدء التتبع (الطريقة القديمة كـ backup)
    document.querySelectorAll('.start-tracking').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const taskId = this.getAttribute('data-task-id');
            if (taskId) {
                console.log('Starting tracking for task (backup):', taskId);
                startTracking(taskId);
            }
        });
    });
    
    // معالجة إيقاف التتبع (الطريقة القديمة كـ backup)
    document.querySelectorAll('.stop-tracking').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const taskId = this.getAttribute('data-task-id');
            if (taskId) {
                console.log('Stopping tracking for task (backup):', taskId);
                stopTracking(taskId);
            }
        });
    });
    
    // تحديث المؤقتات للجلسات النشطة - حساب محلي
    function updateTimers() {
        document.querySelectorAll('[id^="timer-"]').forEach(timerElement => {
            const startedAt = timerElement.getAttribute('data-started-at');
            if (!startedAt) return;
            
            const taskId = timerElement.id.replace('timer-', '');
            const timerTextElement = document.getElementById(`timer-text-${taskId}`);
            if (!timerTextElement) return;
            
            // Laravel timestamp بالثواني، JavaScript Date.now() بالمللي ثانية
            const now = Math.floor(Date.now() / 1000);
            const startTime = parseInt(startedAt);
            const elapsed = now - startTime;
            
            if (elapsed < 0) {
                timerTextElement.textContent = '00:00:00';
                return;
            }
            
            const hours = Math.floor(elapsed / 3600);
            const minutes = Math.floor((elapsed % 3600) / 60);
            const seconds = elapsed % 60;
            
            const timeString = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            timerTextElement.textContent = timeString;
        });
    }
    
    // تحديث المؤقتات كل ثانية
    if (document.querySelectorAll('[id^="timer-"]').length > 0) {
        setInterval(updateTimers, 1000);
        updateTimers(); // تحديث فوري
    }
    
    // بدء التتبع
    function startTracking(taskId) {
        if (!taskId) {
            console.error('Task ID is missing');
            Swal.fire({
                icon: 'error',
                title: 'خطأ!',
                text: 'رقم المهمة غير موجود',
                confirmButtonText: 'حسناً',
                confirmButtonColor: '#dc3545'
            });
            return;
        }
        
        if (!csrfToken) {
            console.error('CSRF token is missing');
            Swal.fire({
                icon: 'error',
                title: 'خطأ!',
                text: 'رمز الأمان غير موجود',
                confirmButtonText: 'حسناً',
                confirmButtonColor: '#dc3545'
            });
            return;
        }
        
        console.log('Sending request to start tracking:', `/admin/tasks/${taskId}/start-tracking`);
        
        // عرض loading
        Swal.fire({
            title: 'جاري البدء...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        fetch(`/admin/tasks/${taskId}/start-tracking`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            credentials: 'same-origin'
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'خطأ في الاستجابة');
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'نجح!',
                    text: 'تم بدء تتبع الوقت بنجاح',
                    confirmButtonText: 'حسناً',
                    confirmButtonColor: '#28a745'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ!',
                    text: data.message || 'حدث خطأ أثناء بدء التتبع',
                    confirmButtonText: 'حسناً',
                    confirmButtonColor: '#dc3545'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'خطأ!',
                text: 'حدث خطأ أثناء بدء التتبع: ' + error.message,
                confirmButtonText: 'حسناً',
                confirmButtonColor: '#dc3545'
            });
        });
    }
    
    // إيقاف التتبع
    function stopTracking(taskId) {
        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: 'هل تريد إيقاف تتبع الوقت لهذه المهمة؟',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'نعم، إيقاف',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                // عرض loading
                Swal.fire({
                    title: 'جاري الإيقاف...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                fetch(`/admin/tasks/${taskId}/stop-tracking`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم الإيقاف!',
                            html: `تم إيقاف التتبع بنجاح<br><strong>الوقت المستغرق: ${data.hours_worked} ساعة</strong>`,
                            confirmButtonText: 'حسناً',
                            confirmButtonColor: '#28a745'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ!',
                            text: data.message || 'حدث خطأ أثناء إيقاف التتبع',
                            confirmButtonText: 'حسناً',
                            confirmButtonColor: '#dc3545'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ!',
                        text: 'حدث خطأ أثناء إيقاف التتبع',
                        confirmButtonText: 'حسناً',
                        confirmButtonColor: '#dc3545'
                    });
                });
            }
        });
    }
    
    // معالجة حذف المهام
    document.querySelectorAll('.delete-task-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const taskId = this.getAttribute('data-task-id');
            const form = this.closest('.delete-task-form');
            
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: 'هل تريد حذف هذه المهمة؟ لا يمكن التراجع عن هذا الإجراء.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
    
    // معالجة تغيير حالة الدفع للجلسات
    document.addEventListener('click', function(e) {
        if (e.target.closest('.toggle-paid-btn')) {
            const button = e.target.closest('.toggle-paid-btn');
            const timeEntryId = button.getAttribute('data-time-entry-id');
            const isPaid = button.getAttribute('data-is-paid') === '1';
            
            const action = isPaid ? 'تحديد كغير مدفوعة' : 'تحديد كمدفوعة';
            
            Swal.fire({
                title: 'تغيير حالة الدفع',
                text: `هل تريد ${action}؟`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: isPaid ? '#ffc107' : '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'نعم',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/time-entries/${timeEntryId}/toggle-paid`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // تحديث الزر
                            const newIsPaid = data.is_paid;
                            button.setAttribute('data-is-paid', newIsPaid ? '1' : '0');
                            
                            if (newIsPaid) {
                                button.className = 'btn btn-sm toggle-paid-btn btn-success';
                                button.innerHTML = '<i class="fas fa-check-circle"></i> مدفوعة';
                            } else {
                                button.className = 'btn btn-sm toggle-paid-btn btn-warning';
                                button.innerHTML = '<i class="fas fa-clock"></i> غير مدفوعة';
                            }
                            
                            // تحديث لون الحدود
                            const infoItem = button.closest('.info-item');
                            if (infoItem) {
                                infoItem.style.borderRight = `3px solid ${newIsPaid ? '#28a745' : '#ffc107'}`;
                            }
                            
                            // تحديث لون الأيقونة
                            const icon = infoItem.querySelector('.fa-clock');
                            if (icon) {
                                icon.className = `fas fa-clock ${newIsPaid ? 'text-success' : 'text-warning'}`;
                            }
                            
                            Swal.fire({
                                icon: 'success',
                                title: 'نجح!',
                                text: data.message,
                                confirmButtonText: 'حسناً',
                                confirmButtonColor: '#28a745',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ!',
                                text: data.message || 'حدث خطأ أثناء تحديث الحالة',
                                confirmButtonText: 'حسناً',
                                confirmButtonColor: '#dc3545'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ!',
                            text: 'حدث خطأ أثناء تحديث الحالة',
                            confirmButtonText: 'حسناً',
                            confirmButtonColor: '#dc3545'
                        });
                    });
                }
            });
        }
    });
    
    // معالجة حذف الجلسات
    document.addEventListener('click', function(e) {
        if (e.target.closest('.delete-session-btn')) {
            const button = e.target.closest('.delete-session-btn');
            const timeEntryId = button.getAttribute('data-time-entry-id');
            
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: 'هل تريد حذف هذه الجلسة؟ لا يمكن التراجع عن هذا الإجراء.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f56565',
                cancelButtonColor: '#718096',
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    // إنشاء form للحذف
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/time-entries/${timeEntryId}`;
                    
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    
                    form.appendChild(csrfInput);
                    form.appendChild(methodInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    });
    
    // المؤقتات يتم تحديثها تلقائياً كل ثانية في دالة updateTimers أعلاه
});
</script>
@endsection

