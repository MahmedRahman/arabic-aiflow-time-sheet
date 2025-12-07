<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تسجيل الدخول - نظام إدارة الوقت</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4a5568;
            --primary-hover: #2d3748;
            --success-color: #48bb78;
            --bg-light: #f7fafc;
            --bg-white: #ffffff;
            --border-color: #e2e8f0;
            --text-primary: #2d3748;
            --text-secondary: #718096;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.05);
            --shadow-md: 0 2px 6px rgba(0,0,0,0.08);
            --shadow-lg: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Tajawal', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            min-height: 100vh;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            width: 100%;
            max-width: 450px;
        }
        
        .login-card {
            background: var(--bg-white);
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            border: 1px solid var(--border-color);
        }
        
        .login-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }
        
        .login-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .login-icon i {
            font-size: 2.5rem;
            color: white;
        }
        
        .login-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0 0 10px;
            color: white;
        }
        
        .login-header p {
            font-size: 0.95rem;
            opacity: 0.9;
            margin: 0;
        }
        
        .login-body {
            padding: 40px 30px;
        }
        
        .form-label {
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 8px;
            font-size: 0.95rem;
        }
        
        .input-group {
            position: relative;
            margin-bottom: 20px;
        }
        
        .input-group-text {
            background: var(--bg-light);
            border: 1px solid var(--border-color);
            border-left: none;
            border-radius: 12px 0 0 12px;
            color: var(--text-secondary);
            padding: 12px 16px;
        }
        
        .form-control {
            border: 1px solid var(--border-color);
            border-right: none;
            border-radius: 0 12px 12px 0;
            padding: 12px 16px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: var(--bg-white);
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(74, 85, 104, 0.1);
            background: var(--bg-white);
        }
        
        .form-control.is-invalid {
            border-color: #f56565;
        }
        
        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(245, 101, 101, 0.1);
        }
        
        .btn-login {
            background: var(--primary-color);
            border: none;
            border-radius: 12px;
            padding: 14px 24px;
            font-size: 1rem;
            font-weight: 500;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            margin-top: 10px;
        }
        
        .btn-login:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(74, 85, 104, 0.3);
            color: white;
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .error-message {
            color: #f56565;
            font-size: 0.875rem;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .error-message i {
            font-size: 0.875rem;
        }
        
        .alert-container {
            margin-bottom: 20px;
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            padding: 12px 16px;
            font-size: 0.9rem;
        }
        
        @media (max-width: 576px) {
            .login-header {
                padding: 30px 20px;
            }
            
            .login-body {
                padding: 30px 20px;
            }
            
            .login-icon {
                width: 70px;
                height: 70px;
            }
            
            .login-icon i {
                font-size: 2rem;
            }
            
            .login-header h1 {
                font-size: 1.5rem;
            }
        }
        
        .input-group:focus-within .input-group-text {
            border-color: var(--primary-color);
            background: var(--bg-light);
        }
        
        .input-group:focus-within .form-control {
            border-color: var(--primary-color);
        }
        
        .password-toggle {
            background: var(--bg-light);
            border: 1px solid var(--border-color);
            border-right: none;
            border-radius: 0;
            color: var(--text-secondary);
            padding: 12px 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .password-toggle:hover {
            background: var(--primary-color);
            color: white;
        }
        
        .password-toggle:active {
            transform: scale(0.95);
        }
        
        .input-group .form-control:last-child {
            border-radius: 0 12px 12px 0;
        }
        
        .input-group.has-password-toggle .form-control {
            border-radius: 0;
        }
        
        .input-group.has-password-toggle .password-toggle {
            border-radius: 0 12px 12px 0;
        }
        
        .admin-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 14px;
            padding: 16px;
            margin-bottom: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            box-shadow: 0 3px 10px rgba(102, 126, 234, 0.2);
        }
        
        .admin-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 16px rgba(102, 126, 234, 0.3);
            border-color: rgba(255, 255, 255, 0.3);
        }
        
        .admin-card:active {
            transform: translateY(0);
        }
        
        .admin-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }
        
        .admin-card-title {
            color: white;
            font-size: 0.95rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .admin-card-title i {
            font-size: 1.1rem;
        }
        
        .admin-card-badge {
            background: rgba(255, 255, 255, 0.25);
            color: white;
            padding: 3px 10px;
            border-radius: 16px;
            font-size: 0.75rem;
            font-weight: 500;
            backdrop-filter: blur(10px);
            white-space: nowrap;
        }
        
        .admin-card-body {
            color: rgba(255, 255, 255, 0.95);
        }
        
        .admin-credential {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
            font-size: 0.85rem;
        }
        
        .admin-credential:last-child {
            margin-bottom: 0;
        }
        
        .admin-credential i {
            width: 18px;
            text-align: center;
            opacity: 0.95;
            font-size: 0.9rem;
        }
        
        .admin-credential-label {
            font-weight: 500;
            min-width: 85px;
            font-size: 0.85rem;
            opacity: 0.95;
        }
        
        .admin-credential-value {
            background: rgba(255, 255, 255, 0.2);
            padding: 5px 10px;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            font-weight: 600;
            font-size: 0.85rem;
            backdrop-filter: blur(10px);
            flex: 1;
            text-align: right;
            letter-spacing: 0.5px;
        }
        
        .admin-card-footer {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            text-align: center;
        }
        
        .admin-card-footer-text {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.8rem;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        
        .admin-card-footer-text i {
            animation: pulse 2s infinite;
            font-size: 0.75rem;
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }
        
        @media (max-width: 576px) {
            .admin-card {
                padding: 14px;
            }
            
            .admin-card-title {
                font-size: 0.9rem;
            }
            
            .admin-card-badge {
                font-size: 0.7rem;
                padding: 2px 8px;
            }
            
            .admin-credential {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
                margin-bottom: 10px;
            }
            
            .admin-credential-label {
                min-width: auto;
                font-size: 0.8rem;
            }
            
            .admin-credential-value {
                width: 100%;
                font-size: 0.8rem;
                padding: 4px 8px;
            }
            
            .admin-card-footer-text {
                font-size: 0.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h1>تسجيل الدخول</h1>
                <p>مرحباً بك في نظام إدارة الوقت</p>
            </div>
            
            <div class="login-body">
                @if(session('error'))
                    <div class="alert-container">
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                        </div>
                    </div>
                @endif
                
                <!-- كارت بيانات تسجيل الدخول للأدمن -->
                <div class="admin-card" id="adminCard" onclick="loginAsAdmin()">
                    <div class="admin-card-header">
                        <h3 class="admin-card-title">
                            <i class="fas fa-user-shield"></i>
                            تسجيل دخول سريع - المدير
                        </h3>
                        <span class="admin-card-badge">
                            <i class="fas fa-bolt me-1"></i>انقر للدخول
                        </span>
                    </div>
                    <div class="admin-card-body">
                        <div class="admin-credential">
                            <i class="fas fa-envelope"></i>
                            <span class="admin-credential-label">البريد:</span>
                            <span class="admin-credential-value">admin@example.com</span>
                        </div>
                        <div class="admin-credential">
                            <i class="fas fa-key"></i>
                            <span class="admin-credential-label">كلمة المرور:</span>
                            <span class="admin-credential-value">password</span>
                        </div>
                    </div>
                    <div class="admin-card-footer">
                        <p class="admin-card-footer-text">
                            <i class="fas fa-hand-pointer"></i>
                            اضغط هنا لتسجيل الدخول تلقائياً
                        </p>
                    </div>
                </div>
                
                <div class="text-center mb-4" style="position: relative;">
                    <hr style="margin: 0; border-color: var(--border-color);">
                    <span style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: var(--bg-white); padding: 0 15px; color: var(--text-secondary); font-size: 0.85rem;">
                        أو
                    </span>
                    </div>

                <form method="POST" action="{{ route('login') }}" id="loginForm">
                        @csrf
                        
                    <div class="mb-4">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-2 text-secondary"></i>
                            البريد الإلكتروني
                        </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   placeholder="أدخل بريدك الإلكتروني" 
                                   required 
                                   autocomplete="email"
                                   autofocus>
                        </div>
                        @error('email')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>{{ $message }}</span>
                            </div>
                            @enderror
                        </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-2 text-secondary"></i>
                            كلمة المرور
                        </label>
                        <div class="input-group has-password-toggle">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   placeholder="أدخل كلمة المرور" 
                                   required
                                   autocomplete="current-password">
                            <span class="input-group-text password-toggle" id="togglePassword" title="إظهار/إخفاء كلمة المرور">
                                <i class="fas fa-eye" id="eyeIcon"></i>
                            </span>
                        </div>
                        @error('password')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>{{ $message }}</span>
                            </div>
                            @enderror
                        </div>

                    <button type="submit" class="btn btn-login">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                تسجيل الدخول
                            </button>
                    </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // دالة تسجيل الدخول كأدمن
        function loginAsAdmin() {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const form = document.getElementById('loginForm');
            const adminCard = document.getElementById('adminCard');
            
            // ملء البيانات
            emailInput.value = 'admin@example.com';
            passwordInput.value = 'password';
            
            // إزالة أي أخطاء سابقة
            emailInput.classList.remove('is-invalid');
            passwordInput.classList.remove('is-invalid');
            
            // تأثير بصري على الكارت
            adminCard.style.transform = 'scale(0.98)';
            setTimeout(() => {
                adminCard.style.transform = '';
            }, 150);
            
            // إرسال النموذج تلقائياً بعد تأخير بسيط
            setTimeout(() => {
                form.submit();
            }, 300);
        }
        
        // إظهار/إخفاء كلمة المرور
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (togglePassword && passwordInput && eyeIcon) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    
                    // تغيير الأيقونة
                    if (type === 'text') {
                        eyeIcon.classList.remove('fa-eye');
                        eyeIcon.classList.add('fa-eye-slash');
                    } else {
                        eyeIcon.classList.remove('fa-eye-slash');
                        eyeIcon.classList.add('fa-eye');
                    }
                });
            }
        });
        
        // تحسين تجربة المستخدم
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const submitBtn = form.querySelector('button[type="submit"]');
            
            // إضافة تأثير عند الكتابة
            [emailInput, passwordInput].forEach(input => {
                input.addEventListener('input', function() {
                    if (this.value.trim() !== '') {
                        this.classList.remove('is-invalid');
                    }
                });
            });
            
            // منع إرسال النموذج المتكرر
            form.addEventListener('submit', function(e) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري تسجيل الدخول...';
                
                // إعادة تفعيل الزر بعد 3 ثوانٍ في حالة الفشل
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i>تسجيل الدخول';
                }, 3000);
            });
        });
    </script>
</body>
</html>
