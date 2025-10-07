<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappAgent extends Model
{
    use HasFactory;

    protected $table = 'user_whats_app_business_account';

    protected $fillable = [
        'whats_app_business_account_id',
        'user_id',
    ];

    public function whatsappBusinessAccount()
    {
        return $this->belongsTo(WhatsAppBusinessAccount::class, 'whats_app_business_account_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
