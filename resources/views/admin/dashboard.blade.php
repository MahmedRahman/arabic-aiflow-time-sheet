@extends('layouts.app')

@section('title', 'لوحة تحكم الإدمن')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-tachometer-alt me-2"></i>لوحة التحكم</h2>
    <div class="text-muted">
        <i class="fas fa-calendar me-1"></i>
        {{ date('Y-m-d') }}
    </div>
</div>

<!-- إحصائيات عامة -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['total_clients'] }}</h4>
                        <p class="card-text">إجمالي العملاء</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['total_projects'] }}</h4>
                        <p class="card-text">إجمالي المشاريع</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-project-diagram fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ number_format($stats['total_hours'], 2) }}</h4>
                        <p class="card-text">إجمالي الساعات</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ number_format($stats['total_amount'], 2) }} ج.م</h4>
                        <p class="card-text">إجمالي المبلغ</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-money-bill-wave fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- إحصائيات إضافية -->
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['pending_entries'] }}</h4>
                        <p class="card-text">ساعات في انتظار الموافقة</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-hourglass-half fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-3">
        <div class="card bg-secondary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['total_time_entries'] }}</h4>
                        <p class="card-text">إجمالي سجلات الوقت</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-list fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- إحصائيات الفواتير -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['total_invoices'] }}</h4>
                        <p class="card-text">إجمالي الفواتير</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-file-invoice fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-secondary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['draft_invoices'] }}</h4>
                        <p class="card-text">مسودات</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-edit fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['sent_invoices'] }}</h4>
                        <p class="card-text">مرسلة</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-paper-plane fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['paid_invoices'] }}</h4>
                        <p class="card-text">مدفوعة</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- إحصائيات المبالغ -->
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card bg-light">
            <div class="card-body text-center">
                <h4 class="text-success">{{ number_format($stats['total_invoice_amount'], 2) }} ج.م</h4>
                <p class="text-muted">إجمالي مبلغ الفواتير</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-3">
        <div class="card bg-light">
            <div class="card-body text-center">
                <h4 class="text-primary">{{ number_format($stats['paid_invoice_amount'], 2) }} ج.م</h4>
                <p class="text-muted">المبلغ المدفوع</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- سجلات الوقت الأخيرة -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clock me-2"></i>
                    سجلات الوقت الأخيرة
                </h5>
            </div>
            <div class="card-body">
                @if($recent_entries->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>التاريخ</th>
                                    <th>المشروع</th>
                                    <th>العميل</th>
                                    <th>المطور</th>
                                    <th>الساعات</th>
                                    <th>المبلغ</th>
                                    <th>الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_entries as $entry)
                                <tr>
                                    <td>{{ $entry->date->format('Y-m-d') }}</td>
                                    <td>{{ $entry->project->name }}</td>
                                    <td>{{ $entry->project->client->name }}</td>
                                    <td>{{ $entry->user->name }}</td>
                                    <td>{{ $entry->hours_worked }} ساعة</td>
                                    <td>{{ number_format($entry->total_amount, 2) }} ج.م</td>
                                    <td>
                                        @if($entry->status == 'approved')
                                            <span class="badge bg-success">موافق عليه</span>
                                        @elseif($entry->status == 'rejected')
                                            <span class="badge bg-danger">مرفوض</span>
                                        @else
                                            <span class="badge bg-warning">في الانتظار</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <p>لا توجد سجلات وقت حالياً</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- إحصائيات شهرية -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    الإحصائيات الشهرية
                </h5>
            </div>
            <div class="card-body">
                @if($monthly_data->count() > 0)
                    @foreach($monthly_data as $data)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="mb-0">شهر {{ $data->month }}</h6>
                            <small class="text-muted">{{ number_format($data->total_hours, 2) }} ساعة</small>
                        </div>
                        <div class="text-end">
                            <strong>{{ number_format($data->total_amount, 2) }} ج.م</strong>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-chart-line fa-2x mb-2"></i>
                        <p>لا توجد بيانات شهرية</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- روابط سريعة -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    روابط سريعة
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('admin.invoices.create') }}" class="btn btn-success w-100">
                            <i class="fas fa-file-invoice me-2"></i>إنشاء فاتورة
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('admin.time-entries.create') }}" class="btn btn-primary w-100">
                            <i class="fas fa-plus me-2"></i>إضافة سجل وقت
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('admin.projects.create') }}" class="btn btn-info w-100">
                            <i class="fas fa-project-diagram me-2"></i>إضافة مشروع
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('admin.clients.create') }}" class="btn btn-warning w-100">
                            <i class="fas fa-user-plus me-2"></i>إضافة عميل
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
