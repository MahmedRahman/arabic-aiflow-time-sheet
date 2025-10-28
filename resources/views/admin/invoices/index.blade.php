@extends('layouts.app')

@section('title', 'الفواتير')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-file-invoice me-2"></i>الفواتير</h2>
    <div>
        <a href="{{ route('admin.invoices.create') }}" class="btn btn-primary me-2">
            <i class="fas fa-plus me-2"></i>إنشاء فاتورة جديدة
        </a>
        <a href="{{ route('admin.time-entries.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-clock me-2"></i>سجلات الوقت
        </a>
    </div>
</div>

@if($invoices->count() > 0)
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>رقم الفاتورة</th>
                            <th>العميل</th>
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
                            <td>{{ $invoice->client->name }}</td>
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
                                    <a href="{{ route('admin.invoices.show', $invoice) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.invoices.edit', $invoice) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    @if($invoice->status === 'draft')
                                        <form method="POST" action="{{ route('admin.invoices.send', $invoice) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success" 
                                                    onclick="return confirm('هل أنت متأكد من إرسال هذه الفاتورة؟')"
                                                    title="إرسال">
                                                <i class="fas fa-paper-plane"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    @if($invoice->status === 'sent')
                                        <form method="POST" action="{{ route('admin.invoices.mark-paid', $invoice) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success" 
                                                    onclick="return confirm('هل أنت متأكد من تسجيل هذه الفاتورة كمدفوعة؟')"
                                                    title="تسجيل كمدفوعة">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    @if(in_array($invoice->status, ['draft', 'sent']))
                                        <form method="POST" action="{{ route('admin.invoices.cancel', $invoice) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('هل أنت متأكد من إلغاء هذه الفاتورة؟')"
                                                    title="إلغاء">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
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
        <p class="text-muted">لم يتم إنشاء أي فواتير بعد</p>
        <a href="{{ route('admin.invoices.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>إنشاء فاتورة جديدة
        </a>
    </div>
@endif
@endsection
