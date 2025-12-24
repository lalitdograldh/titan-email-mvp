<?php
namespace App\Http\Controllers;
use App\Models\Campaign;
use App\Models\EmailLog;

class AnalyticsController extends Controller {
    public function index() {
        $totalSent = EmailLog::where('status', 'sent')->count();
        $totalFailed = EmailLog::where('status', 'failed')->count();
        $totalQueued = EmailLog::where('status', 'queued')->count();
        
        $byCampaign = Campaign::withCount([
            'emailLogs as sent_count' => fn($q) => $q->where('status', 'sent'),
            'emailLogs as failed_count' => fn($q) => $q->where('status', 'failed'),
        ])->latest()->limit(10)->get();

        return view('analytics.index', compact('totalSent', 'totalFailed', 'totalQueued', 'byCampaign'));
    }
}
