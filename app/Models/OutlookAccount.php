<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OutlookAccount extends Model {
    protected $fillable = ['name', 'email', 'user_id', 'daily_limit', 'daily_sent', 'is_active'];

    public function scopeAvailable($query) {
        return $query->where('is_active', true)
                     ->whereRaw('daily_sent < daily_limit')
                     ->orderBy('last_used_at');
    }

    public function canSend() {
        return $this->is_active && $this->daily_sent < $this->daily_limit;
    }

    public function resetDailyCount() {
        $this->update([
            'daily_sent' => 0,
            'last_used_at' => null
        ]);
    }
}
