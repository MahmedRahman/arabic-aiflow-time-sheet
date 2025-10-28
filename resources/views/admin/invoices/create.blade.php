@extends('layouts.app')

@section('title', 'إنشاء فاتورة جديدة')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-plus me-2"></i>إنشاء فاتورة جديدة</h2>
    <div>
        <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">بيانات الفاتورة</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.invoices.store') }}" id="invoice-form">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="client_id" class="form-label">العميل <span class="text-danger">*</span></label>
                            <select class="form-select @error('client_id') is-invalid @enderror" 
                                    id="client_id" name="client_id" required>
                                <option value="">اختر العميل</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }} ({{ $client->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('client_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="project_id" class="form-label">المشروع</label>
                            <select class="form-select @error('project_id') is-invalid @enderror" 
                                    id="project_id" name="project_id">
                                <option value="">اختر المشروع (اختياري)</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" 
                                            data-client="{{ $project->client_id }}"
                                            {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }} - {{ $project->client->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('project_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="issue_date" class="form-label">تاريخ الإصدار <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('issue_date') is-invalid @enderror" 
                                   id="issue_date" name="issue_date" value="{{ old('issue_date', date('Y-m-d')) }}" required>
                            @error('issue_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="due_date" class="form-label">تاريخ الاستحقاق <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('due_date') is-invalid @enderror" 
                                   id="due_date" name="due_date" value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}" required>
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tax_rate" class="form-label">معدل الضريبة (%)</label>
                            <input type="number" class="form-control @error('tax_rate') is-invalid @enderror" 
                                   id="tax_rate" name="tax_rate" value="{{ old('tax_rate', 0) }}" 
                                   min="0" max="100" step="0.01">
                            @error('tax_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- عناصر الفاتورة -->
                    <div class="mb-4">
                        <h5>عناصر الفاتورة</h5>
                        <div id="invoice-items">
                            <div class="invoice-item row mb-3">
                                <div class="col-md-5">
                                    <label class="form-label">الوصف <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="items[0][description]" 
                                           placeholder="وصف الخدمة أو المنتج" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">الكمية <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control quantity" name="items[0][quantity]" 
                                           value="1" min="0.01" step="0.01" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">سعر الوحدة <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control unit-price" name="items[0][unit_price]" 
                                           value="0" min="0" step="0.01" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">المجموع</label>
                                    <input type="text" class="form-control total" readonly>
                                </div>
                            </div>
                        </div>
                        
                        <button type="button" class="btn btn-outline-primary" id="add-item">
                            <i class="fas fa-plus me-2"></i>إضافة عنصر
                        </button>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="notes" class="form-label">ملاحظات</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="terms" class="form-label">شروط الدفع</label>
                            <textarea class="form-control @error('terms') is-invalid @enderror" 
                                      id="terms" name="terms" rows="3">{{ old('terms') }}</textarea>
                            @error('terms')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- معاينة الحساب -->
                    <div class="alert alert-info" id="calculation-preview">
                        <h6><i class="fas fa-calculator me-2"></i>معاينة الحساب:</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>المجموع الفرعي:</strong> <span id="subtotal">0.00</span> ج.م</p>
                                <p><strong>الضريبة:</strong> <span id="tax-amount">0.00</span> ج.م</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>المجموع الإجمالي:</strong> <span id="total-amount">0.00</span> ج.م</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary me-2">إلغاء</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>حفظ الفاتورة
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">معلومات إضافية</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>ملاحظة:</strong> سيتم إنشاء رقم فاتورة تلقائياً عند الحفظ.
                </div>
                
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>تحذير:</strong> تأكد من صحة البيانات قبل الحفظ.
                </div>
                
                <div class="alert alert-light">
                    <h6><i class="fas fa-lightbulb me-2"></i>نصائح:</h6>
                    <ul class="mb-0">
                        <li>يمكنك إضافة عدة عناصر للفاتورة</li>
                        <li>سيتم حساب الضريبة تلقائياً</li>
                        <li>يمكنك ربط الفاتورة بمشروع محدد</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const clientSelect = document.getElementById('client_id');
    const projectSelect = document.getElementById('project_id');
    const addItemBtn = document.getElementById('add-item');
    const invoiceItems = document.getElementById('invoice-items');
    let itemIndex = 1;

    // فلترة المشاريع حسب العميل
    clientSelect.addEventListener('change', function() {
        const clientId = this.value;
        const options = projectSelect.querySelectorAll('option[data-client]');
        
        options.forEach(option => {
            if (option.dataset.client === clientId || option.value === '') {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        });
        
        // إعادة تعيين اختيار المشروع
        projectSelect.value = '';
    });

    // إضافة عنصر جديد
    addItemBtn.addEventListener('click', function() {
        const newItem = document.createElement('div');
        newItem.className = 'invoice-item row mb-3';
        newItem.innerHTML = `
            <div class="col-md-5">
                <label class="form-label">الوصف <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="items[${itemIndex}][description]" 
                       placeholder="وصف الخدمة أو المنتج" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">الكمية <span class="text-danger">*</span></label>
                <input type="number" class="form-control quantity" name="items[${itemIndex}][quantity]" 
                       value="1" min="0.01" step="0.01" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">سعر الوحدة <span class="text-danger">*</span></label>
                <input type="number" class="form-control unit-price" name="items[${itemIndex}][unit_price]" 
                       value="0" min="0" step="0.01" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">المجموع</label>
                <div class="d-flex">
                    <input type="text" class="form-control total" readonly>
                    <button type="button" class="btn btn-outline-danger btn-sm ms-1 remove-item">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        
        invoiceItems.appendChild(newItem);
        itemIndex++;
        
        // إضافة مستمعي الأحداث للعنصر الجديد
        addItemEventListeners(newItem);
    });

    // إضافة مستمعي الأحداث للعنصر
    function addItemEventListeners(item) {
        const quantityInput = item.querySelector('.quantity');
        const unitPriceInput = item.querySelector('.unit-price');
        const totalInput = item.querySelector('.total');
        const removeBtn = item.querySelector('.remove-item');

        function calculateTotal() {
            const quantity = parseFloat(quantityInput.value) || 0;
            const unitPrice = parseFloat(unitPriceInput.value) || 0;
            const total = quantity * unitPrice;
            totalInput.value = total.toFixed(2);
            calculateInvoiceTotal();
        }

        quantityInput.addEventListener('input', calculateTotal);
        unitPriceInput.addEventListener('input', calculateTotal);
        
        removeBtn.addEventListener('click', function() {
            item.remove();
            calculateInvoiceTotal();
        });
    }

    // حساب المجموع الإجمالي للفاتورة
    function calculateInvoiceTotal() {
        const items = document.querySelectorAll('.invoice-item');
        let subtotal = 0;
        
        items.forEach(item => {
            const totalInput = item.querySelector('.total');
            const total = parseFloat(totalInput.value) || 0;
            subtotal += total;
        });
        
        const taxRate = parseFloat(document.getElementById('tax_rate').value) || 0;
        const taxAmount = (subtotal * taxRate) / 100;
        const totalAmount = subtotal + taxAmount;
        
        document.getElementById('subtotal').textContent = subtotal.toFixed(2);
        document.getElementById('tax-amount').textContent = taxAmount.toFixed(2);
        document.getElementById('total-amount').textContent = totalAmount.toFixed(2);
    }

    // إضافة مستمعي الأحداث للعنصر الأول
    addItemEventListeners(document.querySelector('.invoice-item'));
    
    // مستمع لمعدل الضريبة
    document.getElementById('tax_rate').addEventListener('input', calculateInvoiceTotal);
});
</script>
@endsection
