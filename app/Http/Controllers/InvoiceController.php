<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Client;
use App\Models\Project;
use App\Models\TimeEntry;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['client', 'project'])
            ->latest()
            ->paginate(20);

        return view('admin.invoices.index', compact('invoices'));
    }

    public function create()
    {
        $clients = Client::all();
        $projects = Project::with('client')->get();
        
        return view('admin.invoices.create', compact('clients', 'projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after:issue_date',
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.amount' => 'nullable|numeric|min:0',
            'items.*.quantity' => 'nullable|numeric|min:0.01',
            'items.*.unit_price' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // إنشاء رقم الفاتورة
            $invoiceNumber = 'INV-' . date('Y') . '-' . str_pad(Invoice::count() + 1, 4, '0', STR_PAD_LEFT);
            
            // حساب المبالغ
            $subtotal = 0;
            foreach ($request->items as $item) {
                // إذا كان هناك مبلغ مباشر، استخدمه
                if (isset($item['amount']) && $item['amount'] > 0) {
                    $subtotal += $item['amount'];
                } else {
                    // حساب من الساعات وسعر الساعة
                    $subtotal += ($item['quantity'] ?? 1) * ($item['unit_price'] ?? 0);
                }
            }
            
            // لا يوجد ضريبة - المجموع الإجمالي = المجموع الفرعي
            $totalAmount = $subtotal;

            // إنشاء الفاتورة
            $invoice = Invoice::create([
                'invoice_number' => $invoiceNumber,
                'client_id' => $request->client_id,
                'project_id' => $request->project_id,
                'issue_date' => $request->issue_date,
                'due_date' => $request->due_date,
                'subtotal' => $subtotal,
                'tax_rate' => 0,
                'tax_amount' => 0,
                'total_amount' => $totalAmount,
                'status' => 'draft',
                'notes' => $request->notes,
                'terms' => $request->terms,
            ]);

            // إضافة عناصر الفاتورة
            foreach ($request->items as $item) {
                // حساب المجموع للعنصر
                $itemTotal = 0;
                if (isset($item['amount']) && $item['amount'] > 0) {
                    $itemTotal = $item['amount'];
                    $quantity = 1;
                    $unitPrice = $item['amount'];
                } else {
                    $quantity = $item['quantity'] ?? 1;
                    $unitPrice = $item['unit_price'] ?? 0;
                    $itemTotal = $quantity * $unitPrice;
                }
                
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'description' => $item['description'],
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total' => $itemTotal,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.invoices.show', $invoice)
                ->with('success', 'تم إنشاء الفاتورة بنجاح.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إنشاء الفاتورة.')
                ->withInput();
        }
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['client', 'project', 'items']);
        return view('admin.invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $clients = Client::all();
        $projects = Project::with('client')->get();
        $invoice->load('items');
        
        return view('admin.invoices.edit', compact('invoice', 'clients', 'projects'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after:issue_date',
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.amount' => 'nullable|numeric|min:0',
            'items.*.quantity' => 'nullable|numeric|min:0.01',
            'items.*.unit_price' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // حساب المبالغ
            $subtotal = 0;
            foreach ($request->items as $item) {
                // إذا كان هناك مبلغ مباشر، استخدمه
                if (isset($item['amount']) && $item['amount'] > 0) {
                    $subtotal += $item['amount'];
                } else {
                    // حساب من الساعات وسعر الساعة
                    $subtotal += $item['quantity'] * $item['unit_price'];
                }
            }
            
            // لا يوجد ضريبة - المجموع الإجمالي = المجموع الفرعي
            $totalAmount = $subtotal;

            // تحديث الفاتورة
            $invoice->update([
                'client_id' => $request->client_id,
                'project_id' => $request->project_id,
                'issue_date' => $request->issue_date,
                'due_date' => $request->due_date,
                'subtotal' => $subtotal,
                'tax_rate' => 0,
                'tax_amount' => 0,
                'total_amount' => $totalAmount,
                'notes' => $request->notes,
                'terms' => $request->terms,
            ]);

            // حذف العناصر القديمة وإضافة الجديدة
            $invoice->items()->delete();
            foreach ($request->items as $item) {
                // حساب المجموع للعنصر
                $itemTotal = 0;
                if (isset($item['amount']) && $item['amount'] > 0) {
                    $itemTotal = $item['amount'];
                    $quantity = 1;
                    $unitPrice = $item['amount'];
                } else {
                    $quantity = $item['quantity'] ?? 1;
                    $unitPrice = $item['unit_price'] ?? 0;
                    $itemTotal = $quantity * $unitPrice;
                }
                
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'description' => $item['description'],
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total' => $itemTotal,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.invoices.show', $invoice)
                ->with('success', 'تم تحديث الفاتورة بنجاح.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تحديث الفاتورة.')
                ->withInput();
        }
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('admin.invoices.index')
            ->with('success', 'تم حذف الفاتورة بنجاح.');
    }

    public function send(Invoice $invoice)
    {
        $invoice->update(['status' => 'sent']);
        return redirect()->back()
            ->with('success', 'تم إرسال الفاتورة للعميل.');
    }

    public function markAsPaid(Invoice $invoice)
    {
        $invoice->update([
            'status' => 'paid',
            'paid_date' => now(),
        ]);
        return redirect()->back()
            ->with('success', 'تم تسجيل الفاتورة كمدفوعة.');
    }

    public function cancel(Invoice $invoice)
    {
        $invoice->update(['status' => 'cancelled']);
        return redirect()->back()
            ->with('success', 'تم إلغاء الفاتورة.');
    }

    public function generateFromTimeEntries(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'time_entries' => 'required|array|min:1',
            'time_entries.*' => 'exists:time_entries,id',
        ]);

        $client = Client::findOrFail($request->client_id);
        $project = $request->project_id ? Project::findOrFail($request->project_id) : null;
        $timeEntries = TimeEntry::whereIn('id', $request->time_entries)
            ->where('status', 'approved')
            ->get();

        return view('admin.invoices.create-from-entries', compact('client', 'project', 'timeEntries'));
    }
}
