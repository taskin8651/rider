<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayoutRequest extends Model
{
    protected $fillable = [
        'driver_id',
        'amount',
        'status',
        'note'
    ];

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
}
