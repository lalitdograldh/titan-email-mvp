<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model {
    protected $fillable = ['name', 'company', 'website', 'email', 'phone'];
    protected $casts = ['phone' => 'array']; // For international formats

    public function emailLogs() {
        return $this->hasMany(EmailLog::class);
    }
}
