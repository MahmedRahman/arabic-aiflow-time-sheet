@extends('layouts.app')

@section('title', 'إضافة سجل وقت جديد')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-plus me-2"></i>إضافة سجل وقت جديد</h2>
    <a href="{{ route('admin.time-entries.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">بيانات سجل الوقت</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.time-entries.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="project_id" class="form-label">المشروع <span class="text-danger">*</span></label>
                            <select class="form-select @error('project_id') is-invalid @enderror" 
                                    id="project_id" name="project_id" required>
                                <option value="">اختر المشروع</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }} - {{ $project->client->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('project_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="user_id" class="form-label">المطور <span class="text-danger">*</span></label>
                            <select class="form-select @error('user_id') is-invalid @enderror" 
                                    id="user_id" name="user_id" required>
                                <option value="">اختر المطور</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="date" class="form-label">التاريخ <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('date') is-invalid @enderror" 
                                   id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="start_time" class="form-label">وقت البداية <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('start_time') is-invalid @enderror" 
                                   id="start_time" name="start_time" value="{{ old('start_time') }}" 
                                   placeholder="09:00" required>
                            <small class="form-text text-muted">نظام 24 ساعة (مثال: 09:00, 14:30)</small>
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="end_time" class="form-label">وقت النهاية <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('end_time') is-invalid @enderror" 
                                   id="end_time" name="end_time" value="{{ old('end_time') }}" 
                                   placeholder="17:00" required>
                            <small class="form-text text-muted">نظام 24 ساعة (مثال: 17:00, 18:30)</small>
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">وصف العمل</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" 
                                  placeholder="وصف العمل المنجز...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- معاينة الحساب -->
                    <div class="alert alert-info" id="calculation-preview" style="display: none;">
                        <h6><i class="fas fa-calculator me-2"></i>معاينة الحساب:</h6>
                        <div id="calculation-details"></div>
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                سيتم حساب المبلغ بناءً على سعر الساعة للمشروع المحدد
                            </small>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.time-entries.index') }}" class="btn btn-secondary me-2">إلغاء</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>حفظ سجل الوقت
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">معلومات إضافية</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>ملاحظة:</strong> سيتم حساب الساعات والمبلغ تلقائياً بناءً على وقت البداية والنهاية وسعر الساعة للمشروع.
                </div>
                
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>تحذير:</strong> تأكد من صحة الأوقات المدخلة قبل الحفظ.
                </div>
                
                <div class="alert alert-light">
                    <h6><i class="fas fa-clock me-2"></i>أمثلة على الأوقات:</h6>
                    <ul class="mb-0">
                        <li><strong>09:00</strong> - 9 صباحاً</li>
                        <li><strong>14:30</strong> - 2:30 ظهراً</li>
                        <li><strong>18:45</strong> - 6:45 مساءً</li>
                        <li><strong>23:00</strong> - 11 مساءً</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    const projectSelect = document.getElementById('project_id');
    const calculationPreview = document.getElementById('calculation-preview');
    const calculationDetails = document.getElementById('calculation-details');
    
    function calculateHours() {
        const startTime = startTimeInput.value;
        const endTime = endTimeInput.value;
        
        if (startTime && endTime) {
            const start = new Date('2000-01-01T' + startTime);
            const end = new Date('2000-01-01T' + endTime);
            
            if (end > start) {
                const diffMs = end - start;
                const diffHours = diffMs / (1000 * 60 * 60);
                
                // تحويل الساعات إلى ساعات ودقائق
                const hours = Math.floor(diffHours);
                const minutes = Math.round((diffHours - hours) * 60);
                
                let timeDisplay = '';
                if (hours > 0 && minutes > 0) {
                    timeDisplay = `${hours} ساعة و ${minutes} دقيقة`;
                } else if (hours > 0) {
                    timeDisplay = `${hours} ساعة`;
                } else {
                    timeDisplay = `${minutes} دقيقة`;
                }
                
                calculationDetails.innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>وقت البداية:</strong> ${startTime}</p>
                            <p><strong>وقت النهاية:</strong> ${endTime}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>المدة:</strong> ${timeDisplay}</p>
                            <p><strong>الساعات:</strong> ${diffHours.toFixed(2)} ساعة</p>
                        </div>
                    </div>
                `;
                calculationPreview.style.display = 'block';
            } else {
                calculationPreview.style.display = 'none';
            }
        } else {
            calculationPreview.style.display = 'none';
        }
    }
    
    startTimeInput.addEventListener('change', calculateHours);
    endTimeInput.addEventListener('change', calculateHours);
});
</script>
@endsection
