<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappStatus extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_status';

    protected $fillable = ['lead_id', 'status', 'user_response'];	


    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
