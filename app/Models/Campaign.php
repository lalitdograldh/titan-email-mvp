<?php
// app/Models/Campaign.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    protected $fillable = [
        'name',
        'subject',
        'body_html',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    const STATUSES = [
        'draft' => 'Draft',
        'queued' => 'Queued',
        'sending' => 'Sending',
        'sent' => 'Sent',
        'failed' => 'Failed',
    ];

    public function emailLogs(): HasMany
    {
        return $this->hasMany(EmailLog::class);
    }

    public function is($status): bool
    {
        return $this->status === $status;
    }
    
}
