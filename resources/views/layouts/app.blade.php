<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'نظام إدارة الوقت')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --primary-hover: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
            --sidebar-bg: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.08);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.12);
            --shadow-lg: 0 8px 24px rgba(0,0,0,0.15);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', 'Tajawal', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
            min-height: 100vh;
        }
        
        .sidebar {
            background: var(--sidebar-bg);
            min-height: 100vh;
            box-shadow: 4px 0 20px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }
        
        .sidebar > div {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.1);
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 3px;
        }
        
        .sidebar h4 {
            font-weight: 700;
            letter-spacing: -0.5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.9);
            padding: 14px 20px;
            border-radius: 12px;
            margin: 6px 0;
            transition: var(--transition);
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }
        
        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            right: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            transition: var(--transition);
        }
        
        .sidebar .nav-link:hover::before {
            right: 100%;
        }
        
        .sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.15);
            color: white;
            transform: translateX(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.25);
            color: white;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        
        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
        }
        
        .main-content {
            background: transparent;
            min-height: 100vh;
            padding: 0;
        }
        
        .main-content > .p-4 {
            padding: 2rem !important;
        }
        
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            background: white;
            overflow: hidden;
        }
        
        .card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }
        
        .card-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 2px solid #e9ecef;
            padding: 1.25rem 1.5rem;
            font-weight: 600;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .btn {
            border-radius: 10px;
            font-weight: 500;
            padding: 0.6rem 1.5rem;
            transition: var(--transition);
            border: none;
            box-shadow: var(--shadow-sm);
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        .btn-primary {
            background: var(--primary-gradient);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-hover);
            color: white;
        }
        
        .btn-outline-primary {
            border: 2px solid #667eea;
            color: #667eea;
        }
        
        .btn-outline-primary:hover {
            background: var(--primary-gradient);
            border-color: transparent;
            color: white;
        }
        
        .btn-outline-light {
            border: 2px solid rgba(255,255,255,0.5);
        }
        
        .btn-outline-light:hover {
            background: rgba(255,255,255,0.2);
            border-color: rgba(255,255,255,0.8);
        }
        
        .input-group-text {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 1px solid #dee2e6;
            border-left: none;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .table {
            border-radius: 12px;
            overflow: hidden;
        }
        
        .table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .table tbody tr {
            transition: var(--transition);
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
            transform: scale(1.01);
        }
        
        .badge {
            padding: 0.5em 0.75em;
            border-radius: 8px;
            font-weight: 500;
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            box-shadow: var(--shadow-sm);
        }
        
        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
        }
        
        .alert-info {
            background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
            color: #0c5460;
        }
        
        .alert-warning {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            color: #856404;
        }
        
        h2, h3, h4, h5 {
            font-weight: 700;
            color: #2d3748;
        }
        
        .sidebar .text-white {
            padding: 1rem;
            background: rgba(255,255,255,0.1);
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }
        
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                z-index: 1000;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-right: 0;
            }
        }
        
        /* Loading animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .card, .alert {
            animation: fadeIn 0.4s ease-out;
        }
        
        /* Avatar styles */
        .avatar {
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        /* Page header */
        .page-header {
            background: white;
            padding: 1.5rem;
            border-radius: 16px;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-sm);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            @auth
            <div class="col-md-3 col-lg-2 sidebar p-0">
                <div class="p-4 d-flex flex-column h-100">
                    <div class="mb-4">
                        <h4 class="text-white mb-0">
                            <i class="fas fa-clock me-2"></i>
                            نظام إدارة الوقت
                        </h4>
                        <small class="text-white-50 d-block mt-1" style="font-size: 0.75rem;">Time Sheet System</small>
                    </div>
                    <nav class="nav flex-column">
                        @if(auth()->user()->isAdmin())
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                لوحة التحكم
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}" href="{{ route('admin.clients.index') }}">
                                <i class="fas fa-users me-2"></i>
                                العملاء
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}" href="{{ route('admin.projects.index') }}">
                                <i class="fas fa-project-diagram me-2"></i>
                                المشاريع
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.time-entries.*') ? 'active' : '' }}" href="{{ route('admin.time-entries.index') }}">
                                <i class="fas fa-clock me-2"></i>
                                ساعات العمل
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.invoices.*') ? 'active' : '' }}" href="{{ route('admin.invoices.index') }}">
                                <i class="fas fa-file-invoice me-2"></i>
                                الفواتير
                            </a>
                        @else
                            <a class="nav-link {{ request()->routeIs('client.dashboard') ? 'active' : '' }}" href="{{ route('client.dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                لوحة التحكم
                            </a>
                            <a class="nav-link {{ request()->routeIs('client.projects') ? 'active' : '' }}" href="{{ route('client.projects') }}">
                                <i class="fas fa-project-diagram me-2"></i>
                                مشاريعي
                            </a>
                            <a class="nav-link {{ request()->routeIs('client.time-entries') ? 'active' : '' }}" href="{{ route('client.time-entries') }}">
                                <i class="fas fa-clock me-2"></i>
                                ساعات العمل
                            </a>
                            <a class="nav-link {{ request()->routeIs('client.reports') ? 'active' : '' }}" href="{{ route('client.reports') }}">
                                <i class="fas fa-chart-bar me-2"></i>
                                التقارير
                            </a>
                            <a class="nav-link {{ request()->routeIs('client.invoices.*') ? 'active' : '' }}" href="{{ route('client.invoices') }}">
                                <i class="fas fa-file-invoice me-2"></i>
                                الفواتير
                            </a>
                        @endif
                        <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.show') }}">
                            <i class="fas fa-user-circle me-2"></i>
                            الملف الشخصي
                        </a>
                    </nav>
                    <div class="mt-auto pt-3" style="border-top: 1px solid rgba(255,255,255,0.2);">
                        <div class="d-flex align-items-center text-white mb-3">
                            <div class="avatar bg-white text-primary rounded-circle me-2 d-flex align-items-center justify-content-center" 
                                 style="width: 40px; height: 40px; font-weight: 600;">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold">{{ auth()->user()->name }}</div>
                                <small class="text-white-50 d-block" style="font-size: 0.75rem;">
                                    {{ auth()->user()->role === 'admin' ? 'مدير' : 'عميل' }}
                                </small>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm w-100">
                                <i class="fas fa-sign-out-alt me-1"></i>
                                تسجيل الخروج
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-9 col-lg-10 main-content">
                <div class="p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
            @else
            <div class="col-12">
                @yield('content')
            </div>
            @endauth
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
