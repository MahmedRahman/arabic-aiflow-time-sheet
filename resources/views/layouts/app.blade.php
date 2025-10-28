<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'نظام إدارة الوقت')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 5px 0;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            color: white;
        }
        .sidebar .nav-link.active {
            background-color: rgba(255,255,255,0.2);
            color: white;
        }
        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            @auth
            <div class="col-md-3 col-lg-2 sidebar p-0">
                <div class="p-3">
                    <h4 class="text-white mb-4">
                        <i class="fas fa-clock me-2"></i>
                        نظام إدارة الوقت
                    </h4>
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
                    </nav>
                </div>
                <div class="p-3 mt-auto">
                    <div class="d-flex align-items-center text-white">
                        <i class="fas fa-user-circle me-2"></i>
                        <span>{{ auth()->user()->name }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="mt-2">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm w-100">
                            <i class="fas fa-sign-out-alt me-1"></i>
                            تسجيل الخروج
                        </button>
                    </form>
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
