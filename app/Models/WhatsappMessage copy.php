<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'whats_app_conversation_id',
        'whatsapp_business_account_id',
        'message_id',
        'profile_name',
        'from',
        'to',
        'body',
        'quoted_message_id',
        'direction',
        'type',
        'templae_name',
        'error_data',
        'template_data',
        'conversation_id',
        'status',
        'timestamp'
    ];

    protected $table = 'whatsapp_business_messages';

    protected $casts = [
        'status' => 'string', 
        'deleted' => 'boolean',
    ];

    public function conversation()
    {
        return $this->belongsTo(WhatsAppConversation::class, 'whats_app_conversation_id');
    }
    public function media()
    {
        return $this->hasMany(WhatsappBusinessMedia::class, 'message_id');
    }
    public function whatsappBusinessAccount()
    {
        return $this->belongsTo(WhatsappBusinessAccount::class, 'whatsapp_business_account_id');
    }

    public function quoted_message()
    {
        return $this->belongsTo(WhatsappMessage::class, 'quoted_message_id');
    }

}
