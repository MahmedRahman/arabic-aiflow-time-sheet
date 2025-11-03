@extends('layouts.app')

@section('title', 'الملف الشخصي')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-9 col-lg-8">
        <div class="card shadow-lg">
            <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
                <h4 class="card-title mb-0 fw-bold">
                    <i class="fas fa-user-circle me-2"></i>
                    الملف الشخصي
                </h4>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="name" class="form-label fw-semibold">الاسم</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text" style="border-radius: 12px 0 0 12px;">
                                <i class="fas fa-user text-primary"></i>
                            </span>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required
                                   style="border-radius: 0 12px 12px 0; border: 2px solid #e9ecef;">
                        </div>
                        @error('name')
                            <div class="text-danger small mt-2">
                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label fw-semibold">البريد الإلكتروني</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text" style="border-radius: 12px 0 0 12px;">
                                <i class="fas fa-envelope text-primary"></i>
                            </span>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required
                                   style="border-radius: 0 12px 12px 0; border: 2px solid #e9ecef;">
                        </div>
                        @error('email')
                            <div class="text-danger small mt-2">
                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="role" class="form-label fw-semibold">الدور</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text" style="border-radius: 12px 0 0 12px; background: #e9ecef;">
                                <i class="fas fa-user-tag text-secondary"></i>
                            </span>
                            <input type="text" class="form-control" 
                                   id="role" value="{{ $user->role === 'admin' ? 'مدير' : 'عميل' }}" 
                                   disabled style="border-radius: 0 12px 12px 0; border: 2px solid #e9ecef; background: #f8f9fa;">
                        </div>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>لا يمكن تعديل الدور
                        </small>
                    </div>

                    <hr class="my-4" style="border-top: 2px dashed #dee2e6;">

                    <div class="mb-4">
                        <h5 class="mb-3 fw-bold" style="color: #667eea;">
                            <i class="fas fa-lock me-2"></i>
                            تغيير كلمة المرور
                        </h5>
                        <p class="text-muted small mb-3">اترك الحقول التالية فارغة إذا لم ترد تغيير كلمة المرور</p>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">كلمة المرور الجديدة</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text" style="border-radius: 12px 0 0 12px;">
                                <i class="fas fa-lock text-primary"></i>
                            </span>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" placeholder="كلمة المرور الجديدة (8 أحرف على الأقل)"
                                   style="border-radius: 0 12px 12px 0; border: 2px solid #e9ecef;">
                        </div>
                        @error('password')
                            <div class="text-danger small mt-2">
                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label fw-semibold">تأكيد كلمة المرور</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text" style="border-radius: 12px 0 0 12px;">
                                <i class="fas fa-lock text-primary"></i>
                            </span>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" 
                                   placeholder="أعد إدخال كلمة المرور"
                                   style="border-radius: 0 12px 12px 0; border: 2px solid #e9ecef;">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4 pt-3" style="border-top: 2px solid #e9ecef;">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary btn-lg px-4" style="border-radius: 12px;">
                            <i class="fas fa-arrow-right me-2"></i>
                            إلغاء
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg px-5" style="border-radius: 12px;">
                            <i class="fas fa-save me-2"></i>
                            حفظ التغييرات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

