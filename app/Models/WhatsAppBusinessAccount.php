<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class WhatsAppBusinessAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'account_id',           // WABA ID (WhatsApp Business Account ID)
        'business_id',          // Meta Business ID
        'phone_number',         // Display phone number
        'phone_number_id',      // Phone number ID
        'business_id',          // Business ID
        'access_token',         // Encrypted access token
        'name',                 // Business name
        'currency',             // Account currency
        'timezone',             // Business timezone
        'message_template_limit', // Template limit
        'webhook_verify_token', // Webhook verification token
        'status',               // Account status
        'meta_data',            // Additional metadata
        'last_synced_at',       // Last sync timestamp
    ];

    protected $casts = [
        'meta_data' => 'array',
        'last_synced_at' => 'datetime',
    ];

    protected $table = 'whatsapp_business_accounts';

    /**
     * Get the decrypted access token
     */
    public function getDecryptedAccessTokenAttribute()
    {
        try {
            return $this->access_token ? decrypt($this->access_token) : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Scope for active accounts
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for accounts by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Check if account is connected
     */
    public function getIsConnectedAttribute()
    {
        return $this->status === 'active' && !empty($this->access_token);
    }

    /**
     * Get the webhook URL for this account
     */
    public function getWebhookUrlAttribute()
    {
        return route('webhook.whatsapp', ['accountId' => $this->id]);
    }

    /**
     * Relationships
     */
    public function registeredNumbers(): HasMany
    {
        return $this->hasMany(WhatsAppRegisteredNumber::class, 'business_account_id');
    }

    public function templates(): HasMany
    {
        return $this->hasMany(WhatsAppMessageTemplate::class, 'business_account_id');
    }

    public function flows(): HasMany
    {
        return $this->hasMany(Flow::class, 'business_account_id');
    }

    public function messageLogs(): HasMany
    {
        return $this->hasMany(WhatsAppMessageLog::class, 'business_account_id');
    }

    public function agents()
    {
        return $this->hasMany(WhatsappAgent::class, 'whats_app_business_account_id', 'id');
    }


    public function assignedUsers()
    {
        return $this->belongsToMany(
            User::class, 
            'user_whats_app_business_account', 
            'whats_app_business_account_id', 
            'user_id'
        )->withTimestamps(false);
    }
    
    public function agentsWithUser()
    {
        return $this->hasManyThrough(
            User::class,
            WhatsappAgent::class,
            'whats_app_business_account_id', 
            'id', 
            'id', 
            'user_id' 
        );
    }

    public function users()
    {
        return $this->hasManyThrough(
            User::class,
            WhatsappAgent::class,
            'whats_app_business_account_id',
            'id',
            'id',
            'user_id'
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function messages()
    {
        return $this->hasMany(WhatsappMessage::class);
    }

    public function conversations() {
        return $this->hasMany(WhatsAppConversation::class);
    }

    /**
     * Get template statistics
     */
    public function getTemplateStatisticsAttribute()
    {
        return [
            'total' => $this->templates()->count(),
            'approved' => $this->templates()->where('status', 'APPROVED')->count(),
            'pending' => $this->templates()->where('status', 'PENDING')->count(),
            'rejected' => $this->templates()->where('status', 'REJECTED')->count(),
        ];
    }

    /**
     * Get message statistics
     */
    public function getMessageStatisticsAttribute()
    {
        return [
            'sent' => $this->messageLogs()->where('status', 'sent')->count(),
            'delivered' => $this->messageLogs()->where('status', 'delivered')->count(),
            'read' => $this->messageLogs()->where('status', 'read')->count(),
            'failed' => $this->messageLogs()->where('status', 'failed')->count(),
        ];
    }

    /**
     * Update sync timestamp
     */
    public function markAsSynced()
    {
        $this->update(['last_synced_at' => now()]);
    }

    /**
     * Check if needs sync (more than 24 hours since last sync)
     */
    public function needsSync()
    {
        return !$this->last_synced_at || $this->last_synced_at->diffInHours(now()) > 24;
    }

    /**
     * Boot method for event handling
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->webhook_verify_token)) {
                $model->webhook_verify_token = \Illuminate\Support\Str::random(40);
            }
        });
    }
}