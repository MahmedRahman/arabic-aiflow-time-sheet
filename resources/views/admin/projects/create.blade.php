@extends('layouts.app')

@section('title', 'إضافة مشروع جديد')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-plus me-2"></i>إضافة مشروع جديد</h2>
    <a href="{{ route('admin.projects.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">بيانات المشروع</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.projects.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="client_id" class="form-label">العميل <span class="text-danger">*</span></label>
                        <select class="form-select @error('client_id') is-invalid @enderror" 
                                id="client_id" name="client_id" required>
                            <option value="">اختر العميل</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }} ({{ $client->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">اسم المشروع <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">وصف المشروع</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="hourly_rate" class="form-label">سعر الساعة الافتراضي (ج.م)</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('hourly_rate') is-invalid @enderror" 
                                   id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate') }}">
                            <small class="form-text text-muted">سعر الساعة الافتراضي للمشروع (اختياري)</small>
                            @error('hourly_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">حالة المشروع <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="">اختر الحالة</option>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>نشط</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                                <option value="on_hold" {{ old('status') == 'on_hold' ? 'selected' : '' }}>متوقف</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- قسم الموظفين -->
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="fas fa-user-tie me-2"></i>الموظفين في المشروع
                        </label>
                        <div id="users-container">
                            <div class="user-item row mb-3">
                                <div class="col-md-6">
                                    <select class="form-select user-select" name="users[]">
                                        <option value="">اختر الموظف</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <input type="number" step="0.01" min="0" class="form-control hourly-rate-input" 
                                           name="hourly_rates[]" placeholder="سعر الساعة (ج.م)">
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-outline-danger btn-sm remove-user" style="display: none;">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="add-user-btn">
                            <i class="fas fa-plus me-1"></i>إضافة موظف آخر
                        </button>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary me-2">إلغاء</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>حفظ المشروع
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
                    <strong>ملاحظة:</strong> تأكد من اختيار العميل الصحيح قبل إنشاء المشروع.
                </div>
                
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>تحذير:</strong> سعر الساعة سيتم استخدامه لحساب المبالغ في سجلات الوقت.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const usersContainer = document.getElementById('users-container');
    const addUserBtn = document.getElementById('add-user-btn');
    const users = @json($users);
    
    // إضافة موظف جديد
    addUserBtn.addEventListener('click', function() {
        const userItem = document.createElement('div');
        userItem.className = 'user-item row mb-3';
        userItem.innerHTML = `
            <div class="col-md-6">
                <select class="form-select user-select" name="users[]">
                    <option value="">اختر الموظف</option>
                    ${users.map(user => `<option value="${user.id}">${user.name}</option>`).join('')}
                </select>
            </div>
            <div class="col-md-5">
                <input type="number" step="0.01" min="0" class="form-control hourly-rate-input" 
                       name="hourly_rates[]" placeholder="سعر الساعة (ج.م)">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-outline-danger btn-sm remove-user">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        usersContainer.appendChild(userItem);
        updateRemoveButtons();
    });
    
    // حذف موظف
    usersContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-user')) {
            e.target.closest('.user-item').remove();
            updateRemoveButtons();
        }
    });
    
    // تحديث أزرار الحذف
    function updateRemoveButtons() {
        const userItems = usersContainer.querySelectorAll('.user-item');
        userItems.forEach((item, index) => {
            const removeBtn = item.querySelector('.remove-user');
            if (userItems.length > 1) {
                removeBtn.style.display = 'block';
            } else {
                removeBtn.style.display = 'none';
            }
        });
    }
    
    updateRemoveButtons();
});
</script>
@endsection
