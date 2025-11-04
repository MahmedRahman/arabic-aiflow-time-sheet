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

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <h5><i class="fas fa-exclamation-triangle me-2"></i>يرجى تصحيح الأخطاء التالية:</h5>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

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
                    
                    <!-- عناصر الفاتورة -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">عناصر الفاتورة</h5>
                            <div class="btn-group" role="group">
                                <input type="radio" class="btn-check" name="entry_type" id="entry_type_amount" value="amount" checked>
                                <label class="btn btn-outline-primary" for="entry_type_amount">
                                    <i class="fas fa-money-bill me-1"></i>إدخال المبلغ مباشرة
                                </label>
                                
                                <input type="radio" class="btn-check" name="entry_type" id="entry_type_hours" value="hours">
                                <label class="btn btn-outline-primary" for="entry_type_hours">
                                    <i class="fas fa-clock me-1"></i>ساعات العمل
                                </label>
                            </div>
                        </div>
                        
                        @if($errors->has('items') || $errors->has('items.*'))
                            <div class="alert alert-danger mb-3">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <strong>يرجى إدخال بيانات صحيحة للعناصر:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->get('items.*.description') as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                    @if($errors->has('items'))
                                        @foreach($errors->get('items') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        @endif
                        
                        <div id="invoice-items">
                            <div class="invoice-item row mb-3" data-entry-type="amount">
                                <div class="col-md-6">
                                    <label class="form-label">الوصف</label>
                                    <input type="text" class="form-control @error('items.0.description') is-invalid @enderror" 
                                           name="items[0][description]" 
                                           value="{{ old('items.0.description') }}"
                                           placeholder="وصف الخدمة أو المنتج (اختياري)">
                                    @error('items.0.description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label">المبلغ <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control item-amount @error('items.0.amount') is-invalid @enderror" 
                                           name="items[0][amount]" 
                                           value="{{ old('items.0.amount', '0') }}" 
                                           min="0" step="0.01" required>
                                    @error('items.0.amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <input type="hidden" class="form-control quantity" name="items[0][quantity]" value="1">
                                    <input type="hidden" class="form-control unit-price" name="items[0][unit_price]" value="0">
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" class="btn btn-outline-danger btn-sm w-100 remove-item" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="invoice-item row mb-3 d-none" data-entry-type="hours">
                                <div class="col-md-4">
                                    <label class="form-label">الوصف</label>
                                    <input type="text" class="form-control @error('items.0.description') is-invalid @enderror" 
                                           name="items[0][description]" 
                                           value="{{ old('items.0.description') }}"
                                           placeholder="وصف العمل">
                                    @error('items.0.description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">عدد الساعات <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control item-hours @error('items.0.hours') is-invalid @enderror" 
                                           name="items[0][hours]" 
                                           value="{{ old('items.0.hours', '0') }}" 
                                           min="0" step="0.01">
                                    @error('items.0.hours')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">سعر الساعة <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control item-hourly-rate @error('items.0.hourly_rate') is-invalid @enderror" 
                                           name="items[0][hourly_rate]" 
                                           value="{{ old('items.0.hourly_rate', '0') }}" 
                                           min="0" step="0.01">
                                    @error('items.0.hourly_rate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">المجموع</label>
                                    <input type="text" class="form-control item-total" readonly>
                                    <input type="hidden" class="form-control quantity" name="items[0][quantity]" value="1">
                                    <input type="hidden" class="form-control unit-price" name="items[0][unit_price]" value="0">
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" class="btn btn-outline-danger btn-sm w-100 remove-item" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
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
                            <div class="col-md-12 text-center">
                                <p class="mb-0"><strong>المجموع الإجمالي:</strong> <span id="total-amount" class="fs-4 text-primary">0.00</span> ج.م</p>
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
                        <li>يمكنك إدخال المبلغ مباشرة أو استخدام الساعات وسعر الساعة</li>
                        <li>سيتم حساب المجموع الإجمالي تلقائياً</li>
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
    const entryTypeRadios = document.querySelectorAll('input[name="entry_type"]');
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

    // تغيير نوع الإدخال
    entryTypeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            const entryType = this.value;
            const items = document.querySelectorAll('.invoice-item');
            
            items.forEach(item => {
                if (item.dataset.entryType === entryType) {
                    item.classList.remove('d-none');
                    // إظهار/إخفاء الحقول المطلوبة وتحديث الحقول المخفية
                    if (entryType === 'amount') {
                        const amountInput = item.querySelector('.item-amount');
                        const quantityInput = item.querySelector('.quantity');
                        const unitPriceInput = item.querySelector('.unit-price');
                        if (amountInput) {
                            amountInput.required = true;
                            // تحديث الحقول المخفية
                            if (quantityInput && unitPriceInput) {
                                const amount = parseFloat(amountInput.value) || 0;
                                quantityInput.value = 1;
                                unitPriceInput.value = amount;
                            }
                        }
                        const hoursInput = item.querySelector('.item-hours');
                        const hourlyRateInput = item.querySelector('.item-hourly-rate');
                        if (hoursInput) hoursInput.required = false;
                        if (hourlyRateInput) hourlyRateInput.required = false;
                    } else {
                        const hoursInput = item.querySelector('.item-hours');
                        const hourlyRateInput = item.querySelector('.item-hourly-rate');
                        const quantityInput = item.querySelector('.quantity');
                        const unitPriceInput = item.querySelector('.unit-price');
                        if (hoursInput && hourlyRateInput) {
                            hoursInput.required = true;
                            hourlyRateInput.required = true;
                            // تحديث الحقول المخفية
                            if (quantityInput && unitPriceInput) {
                                const hours = parseFloat(hoursInput.value) || 0;
                                const hourlyRate = parseFloat(hourlyRateInput.value) || 0;
                                quantityInput.value = hours;
                                unitPriceInput.value = hourlyRate;
                            }
                        }
                        const amountInput = item.querySelector('.item-amount');
                        if (amountInput) amountInput.required = false;
                    }
                } else {
                    item.classList.add('d-none');
                    // إلغاء required للحقول المخفية
                    const hiddenInputs = item.querySelectorAll('input[required]');
                    hiddenInputs.forEach(input => {
                        if (input.type !== 'hidden') {
                            input.required = false;
                        }
                    });
                }
            });
            
            calculateInvoiceTotal();
        });
    });

    // إضافة عنصر جديد
    addItemBtn.addEventListener('click', function() {
        const activeEntryType = document.querySelector('input[name="entry_type"]:checked').value;
        const newItem = document.createElement('div');
        newItem.className = 'invoice-item row mb-3';
        newItem.setAttribute('data-entry-type', activeEntryType);
        
        if (activeEntryType === 'amount') {
            newItem.innerHTML = `
                <div class="col-md-6">
                    <label class="form-label">الوصف</label>
                    <input type="text" class="form-control" name="items[${itemIndex}][description]" 
                           placeholder="وصف الخدمة أو المنتج (اختياري)">
                </div>
                <div class="col-md-5">
                    <label class="form-label">المبلغ <span class="text-danger">*</span></label>
                    <input type="number" class="form-control item-amount" name="items[${itemIndex}][amount]" 
                           value="0" min="0" step="0.01" required>
                    <input type="hidden" class="form-control quantity" name="items[${itemIndex}][quantity]" value="1">
                    <input type="hidden" class="form-control unit-price" name="items[${itemIndex}][unit_price]" value="0">
                </div>
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-outline-danger btn-sm w-100 remove-item">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;
        } else {
            newItem.innerHTML = `
                <div class="col-md-4">
                    <label class="form-label">الوصف</label>
                    <input type="text" class="form-control" name="items[${itemIndex}][description]" 
                           placeholder="وصف العمل (اختياري)">
                </div>
                <div class="col-md-2">
                    <label class="form-label">عدد الساعات <span class="text-danger">*</span></label>
                    <input type="number" class="form-control item-hours" name="items[${itemIndex}][hours]" 
                           value="0" min="0" step="0.01" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">سعر الساعة <span class="text-danger">*</span></label>
                    <input type="number" class="form-control item-hourly-rate" name="items[${itemIndex}][hourly_rate]" 
                           value="0" min="0" step="0.01" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">المجموع</label>
                    <input type="text" class="form-control item-total" readonly>
                    <input type="hidden" class="form-control quantity" name="items[${itemIndex}][quantity]" value="1">
                    <input type="hidden" class="form-control unit-price" name="items[${itemIndex}][unit_price]" value="0">
                </div>
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-outline-danger btn-sm w-100 remove-item">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;
        }
        
        invoiceItems.appendChild(newItem);
        itemIndex++;
        
        // إضافة مستمعي الأحداث للعنصر الجديد
        addItemEventListeners(newItem);
        
        // تفعيل زر الحذف للعناصر الجديدة
        const removeBtns = document.querySelectorAll('.remove-item');
        if (removeBtns.length > 1) {
            removeBtns.forEach(btn => btn.disabled = false);
        }
    });

    // إضافة مستمعي الأحداث للعنصر
    function addItemEventListeners(item) {
        const entryType = item.dataset.entryType;
        const removeBtn = item.querySelector('.remove-item');
        
        if (entryType === 'amount') {
            const amountInput = item.querySelector('.item-amount');
            const quantityInput = item.querySelector('.quantity');
            const unitPriceInput = item.querySelector('.unit-price');
            
            amountInput.addEventListener('input', function() {
                const amount = parseFloat(this.value) || 0;
                quantityInput.value = 1;
                unitPriceInput.value = amount;
                calculateInvoiceTotal();
            });
        } else {
            const hoursInput = item.querySelector('.item-hours');
            const hourlyRateInput = item.querySelector('.item-hourly-rate');
            const totalInput = item.querySelector('.item-total');
            const quantityInput = item.querySelector('.quantity');
            const unitPriceInput = item.querySelector('.unit-price');
            
            function calculateHoursTotal() {
                const hours = parseFloat(hoursInput.value) || 0;
                const hourlyRate = parseFloat(hourlyRateInput.value) || 0;
                const total = hours * hourlyRate;
                totalInput.value = total.toFixed(2);
                quantityInput.value = hours;
                unitPriceInput.value = hourlyRate;
                calculateInvoiceTotal();
            }
            
            hoursInput.addEventListener('input', calculateHoursTotal);
            hourlyRateInput.addEventListener('input', calculateHoursTotal);
        }
        
        if (removeBtn) {
            removeBtn.addEventListener('click', function() {
                // لا تسمح بحذف العنصر إذا كان واحد فقط
                const allItems = document.querySelectorAll('.invoice-item:not(.d-none)');
                if (allItems.length > 1) {
                    item.remove();
                    calculateInvoiceTotal();
                    
                    // إذا بقي عنصر واحد فقط، تعطيل زر الحذف
                    const remainingItems = document.querySelectorAll('.invoice-item:not(.d-none)');
                    if (remainingItems.length === 1) {
                        const lastRemoveBtn = remainingItems[0].querySelector('.remove-item');
                        if (lastRemoveBtn) {
                            lastRemoveBtn.disabled = true;
                        }
                    }
                }
            });
        }
    }

    // حساب المجموع الإجمالي للفاتورة
    function calculateInvoiceTotal() {
        const activeEntryType = document.querySelector('input[name="entry_type"]:checked').value;
        const items = document.querySelectorAll('.invoice-item:not(.d-none)');
        let totalAmount = 0;
        
        items.forEach(item => {
            if (activeEntryType === 'amount') {
                const amountInput = item.querySelector('.item-amount');
                const amount = parseFloat(amountInput.value) || 0;
                totalAmount += amount;
            } else {
                const totalInput = item.querySelector('.item-total');
                const total = parseFloat(totalInput.value) || 0;
                totalAmount += total;
            }
        });
        
        document.getElementById('total-amount').textContent = totalAmount.toFixed(2);
    }

    // إضافة مستمعي الأحداث للعناصر الموجودة
    document.querySelectorAll('.invoice-item').forEach(item => {
        addItemEventListeners(item);
    });
    
    // تهيئة العنصر الأول عند التحميل
    const activeEntryType = document.querySelector('input[name="entry_type"]:checked').value;
    const firstItem = document.querySelector('.invoice-item');
    if (firstItem) {
        if (activeEntryType === 'amount') {
            const amountInput = firstItem.querySelector('.item-amount');
            if (amountInput && amountInput.value && parseFloat(amountInput.value) > 0) {
                const quantityInput = firstItem.querySelector('.quantity');
                const unitPriceInput = firstItem.querySelector('.unit-price');
                if (quantityInput) quantityInput.value = 1;
                if (unitPriceInput) unitPriceInput.value = amountInput.value;
            }
        } else {
            const hoursInput = firstItem.querySelector('.item-hours');
            const hourlyRateInput = firstItem.querySelector('.item-hourly-rate');
            const quantityInput = firstItem.querySelector('.quantity');
            const unitPriceInput = firstItem.querySelector('.unit-price');
            if (hoursInput && hourlyRateInput && quantityInput && unitPriceInput) {
                const hours = parseFloat(hoursInput.value) || 0;
                const hourlyRate = parseFloat(hourlyRateInput.value) || 0;
                quantityInput.value = hours;
                unitPriceInput.value = hourlyRate;
            }
        }
    }
    
    // حساب أولي
    calculateInvoiceTotal();
    
    // تفعيل/تعطيل أزرار الحذف حسب عدد العناصر
    function updateRemoveButtons() {
        const allItems = document.querySelectorAll('.invoice-item:not(.d-none)');
        const removeBtns = document.querySelectorAll('.remove-item');
        
        if (allItems.length === 1) {
            removeBtns.forEach(btn => btn.disabled = true);
        } else {
            removeBtns.forEach(btn => btn.disabled = false);
        }
    }
    
    // تحديث الأزرار عند التبديل بين الأنواع
    entryTypeRadios.forEach(radio => {
        radio.addEventListener('change', updateRemoveButtons);
    });
    
    updateRemoveButtons();
    
    // تحديث الحقول المخفية قبل إرسال النموذج
    const invoiceForm = document.getElementById('invoice-form');
    if (invoiceForm) {
        invoiceForm.addEventListener('submit', function(e) {
            // التأكد من وجود CSRF token
            let csrfToken = document.querySelector('input[name="_token"]');
            if (!csrfToken) {
                // إنشاء CSRF token إذا لم يكن موجوداً
                const metaToken = document.querySelector('meta[name="csrf-token"]');
                if (metaToken) {
                    csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = metaToken.getAttribute('content');
                    invoiceForm.appendChild(csrfToken);
                }
            } else {
                // تحديث CSRF token من meta tag
                const metaToken = document.querySelector('meta[name="csrf-token"]');
                if (metaToken && csrfToken.value !== metaToken.getAttribute('content')) {
                    csrfToken.value = metaToken.getAttribute('content');
                }
            }
            
            const activeEntryType = document.querySelector('input[name="entry_type"]:checked').value;
            const items = document.querySelectorAll('.invoice-item:not(.d-none)');
            
            items.forEach(item => {
                const quantityInput = item.querySelector('.quantity');
                const unitPriceInput = item.querySelector('.unit-price');
                
                if (activeEntryType === 'amount') {
                    const amountInput = item.querySelector('.item-amount');
                    if (amountInput && quantityInput && unitPriceInput) {
                        const amount = parseFloat(amountInput.value) || 0;
                        quantityInput.value = 1;
                        unitPriceInput.value = amount;
                    }
                } else {
                    const hoursInput = item.querySelector('.item-hours');
                    const hourlyRateInput = item.querySelector('.item-hourly-rate');
                    if (hoursInput && hourlyRateInput && quantityInput && unitPriceInput) {
                        const hours = parseFloat(hoursInput.value) || 0;
                        const hourlyRate = parseFloat(hourlyRateInput.value) || 0;
                        quantityInput.value = hours;
                        unitPriceInput.value = hourlyRate;
                    }
                }
            });
            
            // السماح بإرسال النموذج بشكل طبيعي
            return true;
        });
    }
});
</script>
@endsection
