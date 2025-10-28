@extends('layouts.app')

@section('title', 'فواتيري')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-file-invoice me-2"></i>فواتيري</h2>
    <div class="text-muted">
        مرحباً {{ $client->name }}
    </div>
</div>

@if($invoices->count() > 0)
    <!-- ملخص سريع للفواتير -->
    <div class="row mb-4">
        @php
            $totalInvoices = $invoices->count();
            $draftInvoices = $invoices->where('status', 'draft')->count();
            $sentInvoices = $invoices->where('status', 'sent')->count();
            $paidInvoices = $invoices->where('status', 'paid')->count();
            $totalAmount = $invoices->sum('total_amount');
            $paidAmount = $invoices->where('status', 'paid')->sum('total_amount');
        @endphp
        
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h4 class="card-title">{{ $totalInvoices }}</h4>
                    <p class="card-text">إجمالي الفواتير</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h4 class="card-title">{{ $sentInvoices }}</h4>
                    <p class="card-text">مرسلة</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h4 class="card-title">{{ $paidInvoices }}</h4>
                    <p class="card-text">مدفوعة</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h4 class="card-title">{{ number_format($totalAmount, 2) }}</h4>
                    <p class="card-text">إجمالي المبلغ</p>
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
                            <th>رقم الفاتورة</th>
                            <th>المشروع</th>
                            <th>تاريخ الإصدار</th>
                            <th>تاريخ الاستحقاق</th>
                            <th>المبلغ الإجمالي</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $invoice)
                        <tr>
                            <td>
                                <strong>{{ $invoice->invoice_number }}</strong>
                            </td>
                            <td>
                                @if($invoice->project)
                                    {{ $invoice->project->name }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $invoice->issue_date->format('Y-m-d') }}</td>
                            <td>
                                {{ $invoice->due_date->format('Y-m-d') }}
                                @if($invoice->isOverdue())
                                    <span class="badge bg-danger ms-1">متأخرة</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ number_format($invoice->total_amount, 2) }} ج.م</strong>
                            </td>
                            <td>
                                <span class="badge bg-{{ $invoice->status_color }}">
                                    {{ $invoice->status_label }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('client.invoices.show', $invoice) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($invoice->status === 'sent')
                                        <button class="btn btn-sm btn-success" 
                                                onclick="alert('تم إرسال طلب الدفع بنجاح!')"
                                                title="طلب الدفع">
                                            <i class="fas fa-credit-card"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $invoices->links() }}
    </div>
@else
    <div class="text-center py-5">
        <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
        <h4 class="text-muted">لا توجد فواتير</h4>
        <p class="text-muted">لم يتم إنشاء أي فواتير لك بعد</p>
    </div>
@endif
@endsection
