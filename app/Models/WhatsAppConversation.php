<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsAppConversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'whatsapp_business_account_id',
        'contact_number',
        'contact_name',
        'last_message_at',
    ];

    protected $table = 'whatsapp_conversations';

    public function messages()
    {
        return $this->hasMany(WhatsappMessage::class, 'whats_app_conversation_id', 'id');
    }

    public function whatsappBusinessAccount()
    {
        return $this->belongsTo(WhatsAppBusinessAccount::class);
    }
    
    public function latestMessage()
    {
        return $this->hasOne(WhatsappMessage::class, 'whats_app_conversation_id')
                    ->latestOfMany()
                    ->withDefault(function () {
                        return new WhatsappMessage([
                            'body' => null,
                            'created_at' => null,
                            'deleted' => false
                        ]);
                    });
    }

    public function labels()
    {
        return $this->belongsToMany(WhatsappLabel::class, 'whatsapp_label_assignments');
    }

}
