<?php
// app/Models/EmailLog.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class EmailLog extends Model
{
    protected $fillable = [
        'campaign_id', 'lead_id', 'outlook_account_id', 'to_email',
        'status', 'error_message', 'subject', 'body_html'
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($log) {
            // Ensure valid status
            $validStatuses = ['queued', 'sending', 'sent', 'failed'];
            if (!in_array($log->status, $validStatuses)) {
                $log->status = 'queued';
            }
        });
    }

    public function lead() { return $this->belongsTo(Lead::class); }
    public function campaign() { return $this->belongsTo(Campaign::class); }
    public function outlookAccount() { return $this->belongsTo(OutlookAccount::class); }
}
