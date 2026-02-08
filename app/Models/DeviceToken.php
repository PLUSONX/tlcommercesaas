<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeviceToken extends Model
{
    protected $table = 'device_tokens'; 

    protected $connection = 'mysql';

    protected $fillable = [
        'token',
        'platform',
        'user_id',
        'tenant_id',
        'is_active',
        'last_used_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_used_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function markAsUsed(): void
    {
        $this->update([
            'last_used_at' => now(),
        ]);
    }
}

// class DeviceToken extends Model
// {
//     protected $table = 'device_tokens'; 

//     protected $connection = 'mysql';

//     protected $fillable = [
//         'uuid',
//         'user_id',
//         'token',
//         'platform',
//         'device_name',
//         'device_info',
//         'last_used_at',
//     ];

//     protected $casts = [
//         'device_info' => 'array',
//         'last_used_at' => 'datetime',
//     ];

//     /**
//      * Get the user that owns the device token.
//      */
//     public function user(): BelongsTo
//     {
//         return $this->belongsTo(User::class);
//     }

//     /**
//      * Update the last used timestamp.
//      */
//     public function markAsUsed(): void
//     {

// }