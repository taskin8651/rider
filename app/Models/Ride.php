<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ride extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customer_id',
        'driver_id',
        'pickup_location',
        'drop_location',
        'status',

        // Earnings
        'fare',
        'admin_commission',
        'driver_earning',

        // Tracking
        'driver_lat',
        'driver_lng',
    ];

    /* ==========================
       RELATIONSHIPS
    =========================== */

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    /* ==========================
       USER BASED FILTER
    =========================== */

    public function scopeForUser($query, $user)
    {
        if ($user->is_admin) {
            return $query;
        }

        if ($user->roles->contains('title', 'Driver')) {
            return $query->where('driver_id', $user->id);
        }

        return $query->where('customer_id', $user->id);
    }
}
