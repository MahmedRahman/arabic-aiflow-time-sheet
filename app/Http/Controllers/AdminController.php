<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Client;
use App\Models\Project;
use App\Models\TimeEntry;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_clients' => Client::count(),
            'total_projects' => Project::count(),
            'total_time_entries' => TimeEntry::count(),
            'total_hours' => TimeEntry::sum('hours_worked'),
            'total_amount' => TimeEntry::sum('total_amount'),
            'pending_entries' => TimeEntry::where('status', 'pending')->count(),
            'total_invoices' => Invoice::count(),
            'draft_invoices' => Invoice::where('status', 'draft')->count(),
            'sent_invoices' => Invoice::where('status', 'sent')->count(),
            'paid_invoices' => Invoice::where('status', 'paid')->count(),
            'total_invoice_amount' => Invoice::sum('total_amount'),
            'paid_invoice_amount' => Invoice::where('status', 'paid')->sum('total_amount'),
        ];

        $recent_entries = TimeEntry::with(['project.client', 'user'])
            ->latest()
            ->limit(10)
            ->get();

        $monthly_data = TimeEntry::select(
                DB::raw('strftime("%m", created_at) as month'),
                DB::raw('SUM(hours_worked) as total_hours'),
                DB::raw('SUM(total_amount) as total_amount')
            )
            ->whereRaw('strftime("%Y", created_at) = ?', [date('Y')])
            ->groupBy('month')
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_entries', 'monthly_data'));
    }
}
