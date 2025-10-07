<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsAppMessageTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_account_id',
        'template_id',
        'name',
        'category',
        'language',
        'header',
        'body',
        'footer',
        'meta',
        'components',
        'status'
    ];

    protected $casts = [
        'components' => 'array'
    ];

    protected $table = 'whatsapp_message_templates';

    public function businessAccount()
    {
        return $this->belongsTo(WhatsAppBusinessAccount::class, 'business_account_id');
    }


    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'approved' => 'success',
            'pending' => 'warning',
            'rejected' => 'danger',
            default => 'secondary'
        };
    }


    public function getFormattedComponentsAttribute()
    {
        $components = [];

        if ($this->header) {
            $components[] = [
                'type' => 'HEADER',
                'format' => 'TEXT',
                'text' => $this->header
            ];
        }

        $components[] = [
            'type' => 'BODY',
            'text' => $this->body
        ];

        if ($this->footer) {
            $components[] = [
                'type' => 'FOOTER',
                'text' => $this->footer
            ];
        }

        return array_merge($components, $this->components ?? []);
    }
}