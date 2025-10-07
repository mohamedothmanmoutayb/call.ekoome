<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappLabel extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'color', 'user_id', 'whatsapp_business_account_id'];
    
    public function conversations()
    {
        return $this->belongsToMany(WhatsAppConversation::class, 'whatsapp_label_assignments');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function account()
    {
        return $this->belongsTo(WhatsappBusinessAccount::class, 'whatsapp_business_account_id');
    }

}
