<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappBusinessMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'media_id',
        'message_id',
        'mime_type',
        'extension',
        'file_path',
        'caption',
    ];
    protected $table = 'whatsapp_business_media';
    public function message()
    {
        return $this->belongsTo(WhatsappMessage::class, 'message_id');
    }
    public function whatsappBusinessAccount()
    {
        return $this->belongsTo(WhatsappBusinessAccount::class, 'whatsapp_business_account_id');
    }
    public function getMediaUrlAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }
}
