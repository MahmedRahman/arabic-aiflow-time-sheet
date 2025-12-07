<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'نظام إدارة الوقت')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4a5568;
            --primary-hover: #2d3748;
            --secondary-color: #718096;
            --success-color: #48bb78;
            --danger-color: #f56565;
            --warning-color: #ed8936;
            --info-color: #4299e1;
            --sidebar-bg: #2d3748;
            --sidebar-hover: #4a5568;
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
            font-family: 'Segoe UI', 'Tajawal', Tahoma, Geneva, Verdana, sans-serif;
            background: #f7fafc;
            min-height: 100vh;
            color: var(--text-primary);
        }
        
        .sidebar {
            background: var(--sidebar-bg);
            min-height: 100vh;
            box-shadow: 2px 0 8px rgba(0,0,0,0.05);
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
            background: rgba(255,255,255,0.05);
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 3px;
        }
        
        .sidebar h4 {
            font-weight: 600;
            color: white;
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.85);
            padding: 12px 16px;
            border-radius: 6px;
            margin: 4px 0;
            transition: all 0.2s ease;
            font-weight: 500;
        }
        
        .sidebar .nav-link:hover {
            background-color: var(--sidebar-hover);
            color: white;
        }
        
        .sidebar .nav-link.active {
            background-color: var(--sidebar-hover);
            color: white;
            font-weight: 600;
        }
        
        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
            margin-left: 8px;
            margin-right: 0;
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
            border: 1px solid var(--border-color);
            border-radius: 8px;
            box-shadow: var(--shadow-sm);
            transition: all 0.2s ease;
            background: var(--bg-white);
            overflow: hidden;
        }
        
        .card:hover {
            box-shadow: var(--shadow-md);
        }
        
        .card-header {
            background: #f7fafc;
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
        }
        
        .card-body {
            padding: 1.25rem;
        }
        
        .btn {
            border-radius: 6px;
            font-weight: 500;
            padding: 0.5rem 1.25rem;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }
        
        .btn:hover {
            opacity: 0.9;
        }
        
        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
            color: white;
        }
        
        .btn-success {
            background: var(--success-color);
            border-color: var(--success-color);
            color: white;
        }
        
        .btn-danger {
            background: var(--danger-color);
            border-color: var(--danger-color);
            color: white;
        }
        
        .btn-warning {
            background: var(--warning-color);
            border-color: var(--warning-color);
            color: white;
        }
        
        .btn-outline-primary {
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
            background: transparent;
        }
        
        .btn-outline-primary:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        
        .btn-outline-secondary {
            border: 1px solid var(--secondary-color);
            color: var(--secondary-color);
            background: transparent;
        }
        
        .btn-outline-secondary:hover {
            background: var(--secondary-color);
            border-color: var(--secondary-color);
            color: white;
        }
        
        .btn-outline-info {
            border: 1px solid var(--info-color);
            color: var(--info-color);
            background: transparent;
        }
        
        .btn-outline-info:hover {
            background: var(--info-color);
            border-color: var(--info-color);
            color: white;
        }
        
        .btn-outline-warning {
            border: 1px solid var(--warning-color);
            color: var(--warning-color);
            background: transparent;
        }
        
        .btn-outline-warning:hover {
            background: var(--warning-color);
            border-color: var(--warning-color);
            color: white;
        }
        
        .btn-outline-danger {
            border: 1px solid var(--danger-color);
            color: var(--danger-color);
            background: transparent;
        }
        
        .btn-outline-danger:hover {
            background: var(--danger-color);
            border-color: var(--danger-color);
            color: white;
        }
        
        .btn-outline-light {
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
        }
        
        .btn-outline-light:hover {
            background: rgba(255,255,255,0.15);
            border-color: rgba(255,255,255,0.5);
        }
        
        .form-control, .form-select {
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 0.5rem 0.75rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(74, 85, 104, 0.15);
        }
        
        .input-group-text {
            background: #f7fafc;
            border: 1px solid var(--border-color);
        }
        
        .table {
            border-radius: 6px;
            overflow: hidden;
        }
        
        .table thead {
            background: #f7fafc;
            color: var(--text-primary);
            border-bottom: 2px solid var(--border-color);
        }
        
        .table tbody tr {
            transition: all 0.2s ease;
        }
        
        .table tbody tr:hover {
            background-color: #f7fafc;
        }
        
        .badge {
            padding: 0.4rem 0.75rem;
            border-radius: 4px;
            font-weight: 500;
            font-size: 0.85rem;
        }
        
        .badge.bg-primary {
            background: var(--primary-color) !important;
        }
        
        .badge.bg-success {
            background: var(--success-color) !important;
        }
        
        .badge.bg-danger {
            background: var(--danger-color) !important;
        }
        
        .badge.bg-warning {
            background: var(--warning-color) !important;
            color: white;
        }
        
        .badge.bg-info {
            background: var(--info-color) !important;
        }
        
        .badge.bg-secondary {
            background: var(--secondary-color) !important;
        }
        
        .alert {
            border-radius: 6px;
            border: 1px solid;
        }
        
        .alert-success {
            background: #f0fff4;
            border-color: #9ae6b4;
            color: #22543d;
        }
        
        .alert-danger {
            background: #fff5f5;
            border-color: #fc8181;
            color: #742a2a;
        }
        
        .alert-info {
            background: #ebf8ff;
            border-color: #90cdf4;
            color: #2c5282;
        }
        
        .alert-warning {
            background: #fffaf0;
            border-color: #fbd38d;
            color: #744210;
        }
        
        h2, h3, h4, h5 {
            font-weight: 600;
            color: var(--text-primary);
        }
        
        .sidebar .text-white {
            padding: 1rem;
            background: rgba(255,255,255,0.05);
            border-radius: 6px;
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
        
        /* Avatar styles */
        .avatar {
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        /* Page header */
        .page-header {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
        }
        
        /* تحسينات RTL */
        body[dir="rtl"] {
            direction: rtl;
            text-align: right;
        }
        
        /* تحسينات عامة */
        .text-muted {
            color: var(--text-secondary) !important;
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
                        @if(auth()->user()->isAdmin() || auth()->user()->isEmployee())
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                لوحة التحكم
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                <i class="fas fa-user-tie me-2"></i>
                                الموظفين
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}" href="{{ route('admin.clients.index') }}">
                                <i class="fas fa-users me-2"></i>
                                العملاء
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}" href="{{ route('admin.projects.index') }}">
                                <i class="fas fa-project-diagram me-2"></i>
                                المشاريع
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.tasks.*') ? 'active' : '' }}" href="{{ route('admin.tasks.index') }}">
                                <i class="fas fa-tasks me-2"></i>
                                المهام
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
                                    @if(auth()->user()->role === 'admin')
                                        ادمن
                                    @elseif(auth()->user()->role === 'employee')
                                        موظف
                                    @else
                                        عميل
                                    @endif
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
