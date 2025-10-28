<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\TimeEntry;
use App\Models\Client;
use App\Models\User;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        
        // البحث عن العميل المرتبط بالمستخدم
        $client = Client::where('email', $user->email)->first();
        
        if (!$client) {
            return redirect()->route('login')->with('error', 'لم يتم العثور على بيانات العميل.');
        }

        $stats = [
            'total_projects' => Project::where('client_id', $client->id)->count(),
            'total_hours' => TimeEntry::whereHas('project', function($query) use ($client) {
                $query->where('client_id', $client->id);
            })->sum('hours_worked'),
            'total_amount' => TimeEntry::whereHas('project', function($query) use ($client) {
                $query->where('client_id', $client->id);
            })->sum('total_amount'),
            'pending_amount' => TimeEntry::whereHas('project', function($query) use ($client) {
                $query->where('client_id', $client->id);
            })->where('status', 'pending')->sum('total_amount'),
            'pending_entries' => TimeEntry::whereHas('project', function($query) use ($client) {
                $query->where('client_id', $client->id);
            })->where('status', 'pending')->count(),
            'approved_entries' => TimeEntry::whereHas('project', function($query) use ($client) {
                $query->where('client_id', $client->id);
            })->where('status', 'approved')->count(),
            'total_invoices' => Invoice::where('client_id', $client->id)->count(),
            'sent_invoices' => Invoice::where('client_id', $client->id)->where('status', 'sent')->count(),
            'paid_invoices' => Invoice::where('client_id', $client->id)->where('status', 'paid')->count(),
            'total_invoice_amount' => Invoice::where('client_id', $client->id)->sum('total_amount'),
            'paid_invoice_amount' => Invoice::where('client_id', $client->id)->where('status', 'paid')->sum('total_amount'),
        ];

        $recent_entries = TimeEntry::with(['project', 'user'])
            ->whereHas('project', function($query) use ($client) {
                $query->where('client_id', $client->id);
            })
            ->latest()
            ->limit(10)
            ->get();

        return view('client.dashboard', compact('stats', 'recent_entries', 'client'));
    }

    public function projects()
    {
        $user = auth()->user();
        $client = Client::where('email', $user->email)->first();
        
        if (!$client) {
            return redirect()->route('login')->with('error', 'لم يتم العثور على بيانات العميل.');
        }

        $projects = Project::where('client_id', $client->id)
            ->with(['timeEntries' => function($query) {
                $query->latest();
            }])
            ->get();

        return view('client.projects', compact('projects', 'client'));
    }

    public function timeEntries()
    {
        $user = auth()->user();
        $client = Client::where('email', $user->email)->first();
        
        if (!$client) {
            return redirect()->route('login')->with('error', 'لم يتم العثور على بيانات العميل.');
        }

        $timeEntries = TimeEntry::with(['project', 'user'])
            ->whereHas('project', function($query) use ($client) {
                $query->where('client_id', $client->id);
            })
            ->latest()
            ->paginate(20);

        return view('client.time-entries', compact('timeEntries', 'client'));
    }

    public function reports()
    {
        $user = auth()->user();
        $client = Client::where('email', $user->email)->first();
        
        if (!$client) {
            return redirect()->route('login')->with('error', 'لم يتم العثور على بيانات العميل.');
        }

        $monthly_data = TimeEntry::select(
                DB::raw('strftime("%m", date) as month'),
                DB::raw('SUM(hours_worked) as total_hours'),
                DB::raw('SUM(total_amount) as total_amount')
            )
            ->whereHas('project', function($query) use ($client) {
                $query->where('client_id', $client->id);
            })
            ->whereRaw('strftime("%Y", date) = ?', [date('Y')])
            ->groupBy('month')
            ->get();

        $project_stats = Project::where('client_id', $client->id)
            ->withCount('timeEntries')
            ->withSum('timeEntries', 'hours_worked')
            ->withSum('timeEntries', 'total_amount')
            ->get();

        return view('client.reports', compact('monthly_data', 'project_stats', 'client'));
    }

    public function approveTimeEntry(Request $request, TimeEntry $timeEntry)
    {
        $user = auth()->user();
        $client = Client::where('email', $user->email)->first();
        
        if (!$client) {
            return redirect()->route('login')->with('error', 'لم يتم العثور على بيانات العميل.');
        }

        // التحقق من أن سجل الوقت يخص مشاريع العميل
        if ($timeEntry->project->client_id !== $client->id) {
            return redirect()->back()->with('error', 'ليس لديك صلاحية للوصول إلى هذا السجل.');
        }

        $timeEntry->update(['status' => 'approved']);
        
        return redirect()->back()->with('success', 'تم الموافقة على سجل الوقت بنجاح.');
    }

    public function rejectTimeEntry(Request $request, TimeEntry $timeEntry)
    {
        $user = auth()->user();
        $client = Client::where('email', $user->email)->first();
        
        if (!$client) {
            return redirect()->route('login')->with('error', 'لم يتم العثور على بيانات العميل.');
        }

        // التحقق من أن سجل الوقت يخص مشاريع العميل
        if ($timeEntry->project->client_id !== $client->id) {
            return redirect()->back()->with('error', 'ليس لديك صلاحية للوصول إلى هذا السجل.');
        }

        $timeEntry->update(['status' => 'rejected']);
        
        return redirect()->back()->with('success', 'تم رفض سجل الوقت.');
    }

    public function invoices()
    {
        $user = auth()->user();
        $client = Client::where('email', $user->email)->first();
        
        if (!$client) {
            return redirect()->route('login')->with('error', 'لم يتم العثور على بيانات العميل.');
        }

        $invoices = Invoice::where('client_id', $client->id)
            ->with(['project'])
            ->latest()
            ->paginate(20);

        return view('client.invoices', compact('invoices', 'client'));
    }

    public function showInvoice(Invoice $invoice)
    {
        $user = auth()->user();
        $client = Client::where('email', $user->email)->first();
        
        if (!$client) {
            return redirect()->route('login')->with('error', 'لم يتم العثور على بيانات العميل.');
        }

        // التحقق من أن الفاتورة تخص العميل
        if ($invoice->client_id !== $client->id) {
            return redirect()->back()->with('error', 'ليس لديك صلاحية للوصول إلى هذه الفاتورة.');
        }

        $invoice->load(['client', 'project', 'items']);
        return view('client.invoice-show', compact('invoice', 'client'));
    }

    // Admin functions for managing clients
    public function adminIndex()
    {
        $clients = Client::latest()->paginate(20);
        return view('admin.clients.index', compact('clients'));
    }

    public function adminCreate()
    {
        return view('admin.clients.create');
    }

    public function adminStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clients',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $client = Client::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company' => $request->company,
            'address' => $request->address,
            'password' => Hash::make($request->password),
        ]);

        // إنشاء حساب مستخدم للعميل
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'client',
        ]);

        return redirect()->route('admin.clients.index')
            ->with('success', 'تم إنشاء العميل بنجاح.');
    }

    public function adminShow(Client $client)
    {
        $client->load(['projects.timeEntries']);
        return view('admin.clients.show', compact('client'));
    }

    public function adminEdit(Client $client)
    {
        return view('admin.clients.edit', compact('client'));
    }

    public function adminUpdate(Request $request, Client $client)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clients,email,' . $client->id,
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = $request->only(['name', 'email', 'phone', 'company', 'address']);
        
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $client->update($data);

        // تحديث حساب المستخدم
        $user = User::where('email', $client->email)->first();
        if ($user) {
            $userData = ['name' => $request->name, 'email' => $request->email];
            if ($request->password) {
                $userData['password'] = Hash::make($request->password);
            }
            $user->update($userData);
        }

        return redirect()->route('admin.clients.index')
            ->with('success', 'تم تحديث العميل بنجاح.');
    }

    public function adminDestroy(Client $client)
    {
        // حذف حساب المستخدم المرتبط
        $user = User::where('email', $client->email)->first();
        if ($user) {
            $user->delete();
        }

        $client->delete();

        return redirect()->route('admin.clients.index')
            ->with('success', 'تم حذف العميل بنجاح.');
    }
}
