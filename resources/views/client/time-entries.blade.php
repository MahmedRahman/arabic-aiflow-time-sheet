@extends('layouts.app')

@section('title', 'سجلات الوقت')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-clock me-2"></i>سجلات الوقت</h2>
    <div class="text-muted">
        مرحباً {{ $client->name }}
    </div>
</div>

@if($timeEntries->where('status', 'pending')->count() > 0)
<!-- تنبيه للموافقات المعلقة -->
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <strong>تنبيه:</strong> لديك {{ $timeEntries->where('status', 'pending')->count() }} سجل وقت في انتظار موافقتك
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if($timeEntries->count() > 0)
    <!-- فلترة السجلات -->
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-warning me-2">{{ $timeEntries->where('status', 'pending')->count() }}</span>
                        <span>في الانتظار</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-success me-2">{{ $timeEntries->where('status', 'approved')->count() }}</span>
                        <span>موافق عليه</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-danger me-2">{{ $timeEntries->where('status', 'rejected')->count() }}</span>
                        <span>مرفوض</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-info me-2">{{ $timeEntries->count() }}</span>
                        <span>إجمالي</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>التاريخ</th>
                            <th>المشروع</th>
                            <th>المطور</th>
                            <th>وقت البداية</th>
                            <th>وقت النهاية</th>
                            <th>الساعات</th>
                            <th>سعر الساعة</th>
                            <th>المبلغ</th>
                            <th>الحالة</th>
                            <th>الوصف</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($timeEntries as $entry)
                        <tr>
                            <td>{{ $entry->date->format('Y-m-d') }}</td>
                            <td>{{ $entry->project->name }}</td>
                            <td>{{ $entry->user->name }}</td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $entry->start_time }}</span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $entry->end_time }}</span>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $entry->hours_worked }} ساعة</span>
                            </td>
                            <td>{{ number_format($entry->hourly_rate, 2) }} ج.م</td>
                            <td>
                                <strong>{{ number_format($entry->total_amount, 2) }} ج.م</strong>
                            </td>
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
                                @if($entry->description)
                                    <span title="{{ $entry->description }}">{{ Str::limit($entry->description, 30) }}</span>
                                @else
                                    <span class="text-muted">لا يوجد وصف</span>
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
            
            <div class="d-flex justify-content-center">
                {{ $timeEntries->links() }}
            </div>
        </div>
    </div>
@else
    <div class="text-center text-muted py-5">
        <i class="fas fa-clock fa-3x mb-3"></i>
        <h4>لا توجد سجلات وقت حالياً</h4>
        <p>ستظهر سجلات الوقت هنا عند إضافتها</p>
    </div>
@endif
@endsection
