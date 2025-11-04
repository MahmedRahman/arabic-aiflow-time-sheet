@extends('layouts.app')

@section('title', 'إضافة مهمة جديدة')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-plus me-2"></i>إضافة مهمة جديدة</h2>
    <a href="{{ route('admin.tasks.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">بيانات المهمة</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.tasks.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">العنوان <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}" required
                               placeholder="أدخل عنوان المهمة">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">الوصف</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4"
                                  placeholder="أدخل وصف المهمة">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="estimated_time" class="form-label">الوقت المتوقع (بالساعات) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" class="form-control @error('estimated_time') is-invalid @enderror" 
                                   id="estimated_time" name="estimated_time" value="{{ old('estimated_time') }}" required
                                   placeholder="مثال: 8.5">
                            @error('estimated_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">الحالة <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="">اختر الحالة</option>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>في الانتظار</option>
                                <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>قيد التنفيذ</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>مكتملة</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>ملغاة</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="project_id" class="form-label">المشروع (اختياري)</label>
                            <select class="form-select @error('project_id') is-invalid @enderror" 
                                    id="project_id" name="project_id">
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
                            <label for="user_id" class="form-label">المستخدم (اختياري)</label>
                            <select class="form-select @error('user_id') is-invalid @enderror" 
                                    id="user_id" name="user_id">
                                <option value="">اختر المستخدم</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->role === 'admin' ? 'مدير' : 'عميل' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="due_date" class="form-label">تاريخ الاستحقاق (اختياري)</label>
                        <input type="date" class="form-control @error('due_date') is-invalid @enderror" 
                               id="due_date" name="due_date" value="{{ old('due_date') }}">
                        @error('due_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.tasks.index') }}" class="btn btn-secondary me-2">إلغاء</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>حفظ المهمة
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
                    <strong>ملاحظة:</strong> يمكنك ربط المهمة بمشروع أو مستخدم محدد.
                </div>
                
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>الوقت المتوقع:</strong> أدخل الوقت المتوقع لإنجاز المهمة بالساعات (مثال: 8.5 يعني 8 ساعات ونصف).
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

