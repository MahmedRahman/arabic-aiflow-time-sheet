@extends('layouts.app')

@section('title', 'تسجيل الدخول')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0" style="border-radius: 20px; overflow: hidden;">
                <div class="card-body p-5">
                    <div class="text-center mb-5">
                        <div class="mb-4" style="width: 80px; height: 80px; margin: 0 auto; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);">
                            <i class="fas fa-clock fa-2x text-white"></i>
                        </div>
                        <h2 class="fw-bold mb-2" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">تسجيل الدخول</h2>
                        <p class="text-muted">مرحباً بك في نظام إدارة الوقت</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">البريد الإلكتروني</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text" style="border-radius: 12px 0 0 12px;">
                                    <i class="fas fa-envelope text-primary"></i>
                                </span>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" 
                                       placeholder="أدخل بريدك الإلكتروني" required 
                                       style="border-radius: 0 12px 12px 0; border: 2px solid #e9ecef;">
                            </div>
                            @error('email')
                                <div class="text-danger small mt-2">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold">كلمة المرور</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text" style="border-radius: 12px 0 0 12px;">
                                    <i class="fas fa-lock text-primary"></i>
                                </span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" placeholder="أدخل كلمة المرور" required
                                       style="border-radius: 0 12px 12px 0; border: 2px solid #e9ecef;">
                            </div>
                            @error('password')
                                <div class="text-danger small mt-2">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary btn-lg py-3" style="border-radius: 12px; font-size: 1.1rem;">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                تسجيل الدخول
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4 pt-4" style="border-top: 1px solid #e9ecef;">
                        <p class="text-muted mb-3">ليس لديك حساب؟</p>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg w-100" style="border-radius: 12px;">
                            <i class="fas fa-user-plus me-2"></i>
                            إنشاء حساب جديد
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
