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

        // 💰 Fare & earnings
        'fare',
        'admin_commission',
        'driver_earning',

        // 📍 Live tracking
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
       GLOBAL SCOPE (ACCESS RULES)
       Admin   → all rides
       Driver  → own rides
       Customer→ own rides
    =========================== */

    protected static function booted()
{
    static::addGlobalScope('ride_scope', function ($query) {

        $user = auth()->user();

        if (!$user) return;

        // 🛡 Admin → all rides
        if ($user->is_admin) {
            return;
        }

        // 🛵 Driver → own rides
        if ($user->can('accept_ride')) {
            $query->where('driver_id', $user->id);
            return;
        }

        // 👤 Customer → own rides
        if ($user->can('book_ride')) {
            $query->where('customer_id', $user->id);
            return;
        }

    });
}

}
