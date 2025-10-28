@extends('layouts.app')

@section('title', 'فاتورة ' . $invoice->invoice_number)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-file-invoice me-2"></i>فاتورة {{ $invoice->invoice_number }}</h2>
    <div>
        <a href="{{ route('client.invoices') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- بيانات الفاتورة -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">بيانات الفاتورة</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>رقم الفاتورة:</strong> {{ $invoice->invoice_number }}</p>
                        <p><strong>العميل:</strong> {{ $invoice->client->name }}</p>
                        <p><strong>البريد الإلكتروني:</strong> {{ $invoice->client->email }}</p>
                        @if($invoice->client->phone)
                            <p><strong>الهاتف:</strong> {{ $invoice->client->phone }}</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <p><strong>تاريخ الإصدار:</strong> {{ $invoice->issue_date->format('Y-m-d') }}</p>
                        <p><strong>تاريخ الاستحقاق:</strong> {{ $invoice->due_date->format('Y-m-d') }}</p>
                        <p><strong>الحالة:</strong> 
                            <span class="badge bg-{{ $invoice->status_color }}">
                                {{ $invoice->status_label }}
                            </span>
                        </p>
                        @if($invoice->project)
                            <p><strong>المشروع:</strong> {{ $invoice->project->name }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- عناصر الفاتورة -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">تفاصيل الفاتورة</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>الوصف</th>
                                <th>الكمية</th>
                                <th>سعر الوحدة</th>
                                <th>المجموع</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoice->items as $item)
                            <tr>
                                <td>{{ $item->description }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->unit_price, 2) }} ج.م</td>
                                <td><strong>{{ number_format($item->total, 2) }} ج.م</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>المجموع الفرعي:</strong></td>
                                <td><strong>{{ number_format($invoice->subtotal, 2) }} ج.م</strong></td>
                            </tr>
                            @if($invoice->tax_rate > 0)
                            <tr>
                                <td colspan="3" class="text-end"><strong>الضريبة ({{ $invoice->tax_rate }}%):</strong></td>
                                <td><strong>{{ number_format($invoice->tax_amount, 2) }} ج.م</strong></td>
                            </tr>
                            @endif
                            <tr class="table-primary">
                                <td colspan="3" class="text-end"><strong>المجموع الإجمالي:</strong></td>
                                <td><strong>{{ number_format($invoice->total_amount, 2) }} ج.م</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- ملاحظات وشروط -->
        @if($invoice->notes || $invoice->terms)
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">ملاحظات وشروط</h5>
            </div>
            <div class="card-body">
                @if($invoice->notes)
                    <div class="mb-3">
                        <h6>ملاحظات:</h6>
                        <p>{{ $invoice->notes }}</p>
                    </div>
                @endif
                
                @if($invoice->terms)
                    <div>
                        <h6>شروط الدفع:</h6>
                        <p>{{ $invoice->terms }}</p>
                    </div>
                @endif
            </div>
        </div>
        @endif
    </div>
    
    <div class="col-md-4">
        <!-- إحصائيات الفاتورة -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">معلومات الفاتورة</h5>
            </div>
            <div class="card-body">
                <div class="row text-center mb-3">
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-primary mb-1">{{ $invoice->items->count() }}</h4>
                            <small class="text-muted">عدد العناصر</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success mb-1">{{ number_format($invoice->total_amount, 2) }}</h4>
                        <small class="text-muted">المبلغ الإجمالي</small>
                    </div>
                </div>
                
                <div class="row text-center mb-3">
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-info mb-1">{{ number_format($invoice->subtotal, 2) }}</h4>
                            <small class="text-muted">المجموع الفرعي</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-warning mb-1">{{ number_format($invoice->tax_amount, 2) }}</h4>
                        <small class="text-muted">الضريبة</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- معلومات إضافية -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">معلومات إضافية</h5>
            </div>
            <div class="card-body">
                <p><strong>تاريخ الإنشاء:</strong> {{ $invoice->created_at->format('Y-m-d H:i') }}</p>
                <p><strong>آخر تحديث:</strong> {{ $invoice->updated_at->format('Y-m-d H:i') }}</p>
                
                @if($invoice->paid_date)
                    <p><strong>تاريخ الدفع:</strong> {{ $invoice->paid_date->format('Y-m-d') }}</p>
                @endif
                
                @if($invoice->payment_method)
                    <p><strong>طريقة الدفع:</strong> {{ $invoice->payment_method }}</p>
                @endif
                
                @if($invoice->isOverdue())
                    <div class="alert alert-danger mt-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>تنبيه:</strong> هذه الفاتورة متأخرة عن موعد الاستحقاق
                    </div>
                @endif
            </div>
        </div>
        
        <!-- إجراءات الدفع -->
        @if($invoice->status === 'sent')
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">إجراءات الدفع</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-success" 
                            onclick="alert('تم إرسال طلب الدفع بنجاح! سيتم التواصل معك قريباً.')">
                        <i class="fas fa-credit-card me-2"></i>طلب الدفع
                    </button>
                    
                    <button class="btn btn-outline-primary" 
                            onclick="alert('تم إرسال استفسارك بنجاح! سيتم التواصل معك قريباً.')">
                        <i class="fas fa-question-circle me-2"></i>استفسار عن الفاتورة
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
