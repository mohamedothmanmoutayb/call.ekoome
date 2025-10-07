<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappLabelAssignment extends Model
{
    use HasFactory;

    protected $fillable = ['whatsapp_label_id', 'whats_app_conversation_id'];

}
