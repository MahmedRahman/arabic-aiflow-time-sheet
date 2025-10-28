@extends('layouts.app')

@section('title', 'لوحة تحكم العميل')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-tachometer-alt me-2"></i>لوحة التحكم</h2>
    <div class="text-muted">
        <i class="fas fa-calendar me-1"></i>
        {{ date('Y-m-d') }}
    </div>
</div>

<!-- ترحيب بالعميل -->
<div class="alert alert-info">
    <i class="fas fa-user me-2"></i>
    مرحباً {{ $client->name }}، يمكنك متابعة مشاريعك وساعات العمل من هنا
</div>

@if($stats['pending_entries'] > 0)
<!-- تنبيه للموافقات المعلقة -->
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <strong>تنبيه:</strong> لديك {{ $stats['pending_entries'] }} سجل وقت في انتظار موافقتك بقيمة {{ number_format($stats['pending_amount'], 2) }} ج.م
    <a href="{{ route('client.time-entries') }}" class="btn btn-warning btn-sm ms-2">
        <i class="fas fa-eye me-1"></i>مراجعة السجلات
    </a>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- إحصائيات العميل -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['total_projects'] }}</h4>
                        <p class="card-text">مشاريعي</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-project-diagram fa-2x"></i>
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
        <div class="card bg-info text-white">
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
    
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ number_format($stats['pending_amount'], 2) }} ج.م</h4>
                        <p class="card-text">في انتظار موافقتك</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-hourglass-half fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- إحصائيات إضافية -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['pending_entries'] }}</h4>
                        <p class="card-text">سجلات في انتظار الموافقة</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['approved_entries'] }}</h4>
                        <p class="card-text">سجلات موافق عليها</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="card bg-secondary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['pending_entries'] + $stats['approved_entries'] }}</h4>
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
    
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ number_format($stats['total_invoice_amount'], 2) }}</h4>
                        <p class="card-text">إجمالي مبلغ الفواتير</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-money-bill-wave fa-2x"></i>
                    </div>
                </div>
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
                                    <th>المطور</th>
                                    <th>الساعات</th>
                                    <th>المبلغ</th>
                                    <th>الحالة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_entries as $entry)
                                <tr>
                                    <td>{{ $entry->date->format('Y-m-d') }}</td>
                                    <td>{{ $entry->project->name }}</td>
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
                                    <td>
                                        @if($entry->status == 'pending')
                                            <div class="btn-group" role="group">
                                                <form method="POST" action="{{ route('client.time-entries.approve', $entry) }}" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-success" 
                                                            onclick="return confirm('هل أنت متأكد من الموافقة على هذا السجل؟')"
                                                            title="موافقة">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('client.time-entries.reject', $entry) }}" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-danger" 
                                                            onclick="return confirm('هل أنت متأكد من رفض هذا السجل؟')"
                                                            title="رفض">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
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
    
    <!-- روابط سريعة -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-link me-2"></i>
                    روابط سريعة
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('client.projects') }}" class="btn btn-outline-primary">
                        <i class="fas fa-project-diagram me-2"></i>
                        مشاريعي
                    </a>
                    <a href="{{ route('client.time-entries') }}" class="btn btn-outline-info">
                        <i class="fas fa-clock me-2"></i>
                        ساعات العمل
                    </a>
                    <a href="{{ route('client.reports') }}" class="btn btn-outline-success me-2">
                        <i class="fas fa-chart-bar me-2"></i>
                        التقارير
                    </a>
                    <a href="{{ route('client.invoices') }}" class="btn btn-outline-warning">
                        <i class="fas fa-file-invoice me-2"></i>
                        الفواتير
                    </a>
                </div>
            </div>
        </div>
        
        <!-- معلومات العميل -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user me-2"></i>
                    معلوماتي
                </h5>
            </div>
            <div class="card-body">
                <p><strong>الاسم:</strong> {{ $client->name }}</p>
                <p><strong>البريد الإلكتروني:</strong> {{ $client->email }}</p>
                @if($client->phone)
                    <p><strong>الهاتف:</strong> {{ $client->phone }}</p>
                @endif
                @if($client->company)
                    <p><strong>الشركة:</strong> {{ $client->company }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
