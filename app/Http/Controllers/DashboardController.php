<?php
namespace App\Http\Controllers;
use App\Models\Lead;
use App\Models\Campaign;
use App\Models\EmailLog;
use App\Models\OutlookAccount;
use Illuminate\Http\Request;

class DashboardController extends Controller {
    public function index() {
        return view('dashboard', [
            'stats' => [
                'totalLeads' => Lead::count(),
                'totalCampaigns' => Campaign::count(),
                'totalSent' => EmailLog::where('status', 'sent')->count(),
                'totalAccounts' => OutlookAccount::count(),
            ]
        ]);
    }
}
