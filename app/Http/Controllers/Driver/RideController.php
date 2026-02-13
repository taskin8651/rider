<?php
namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Ride;
use App\Models\Setting;
use Illuminate\Http\Request;

class RideController extends Controller
{
    // Driver dashboard → own rides + pending rides
    public function index()
    {
        $user = auth()->user();

        // Pending rides (no driver assigned yet)
        $pendingRides = Ride::withoutGlobalScopes()
            ->whereNull('driver_id')
            ->where('status', 'pending')
            ->latest()
            ->get();

        // Driver's accepted rides (global scope apply hoga)
        $myRides = Ride::latest()->get();

        return view('driver.rides.index', compact('pendingRides', 'myRides'));
    }

    // Driver accepts a ride
    public function accept(Ride $ride)
    {
        // Safety check
        if ($ride->status !== 'pending' || $ride->driver_id !== null) {
            return back()->with('error', 'Ride already accepted');
        }

        $ride->update([
            'driver_id' => auth()->id(),
            'status'    => 'accepted',
        ]);

        return redirect()->back()->with('success', 'Ride accepted successfully');
    }

   public function complete(Ride $ride)
{
    $user = auth()->user();

    // 🔐 Safety checks
    if ($ride->driver_id !== $user->id) {
        abort(403, 'Unauthorized action');
    }

    if ($ride->status !== 'accepted') {
        return back()->with('error', 'Ride cannot be completed');
    }

    /*
     |--------------------------------------------------------------------------
     | Fare Calculation
     |--------------------------------------------------------------------------
     | Abhi demo fixed / random fare
     | (future me distance/time based kar sakte ho)
     */
    $fare = $ride->fare > 0 ? $ride->fare : rand(80, 200);

    /*
     |--------------------------------------------------------------------------
     | Commission Calculation (DB Based)
     |--------------------------------------------------------------------------
     */
    $commissionPercent = Setting::get(
        'admin_commission_percentage',
        20
    );

    $adminCommission = round(($fare * $commissionPercent) / 100, 2);
    $driverEarning   = round($fare - $adminCommission, 2);

    /*
     |--------------------------------------------------------------------------
     | Update Ride
     |--------------------------------------------------------------------------
     */
    $ride->update([
        'status'           => 'completed',
        'fare'             => $fare,
        'admin_commission' => $adminCommission,
        'driver_earning'   => $driverEarning,
    ]);
    

    return redirect()->back()
        ->with('success', 'Ride completed & earnings calculated');
}


public function cancel(Ride $ride)
{
    // Sirf apni accepted ride
    if (
        $ride->driver_id !== auth()->id() ||
        $ride->status !== 'accepted'
    ) {
        abort(403);
    }

    $ride->update([
        'driver_id' => null,
        'status' => 'cancelled'
    ]);

    return back()->with('success', 'Ride cancelled successfully');
}

public function updateLocation(Request $request)
{
    $request->validate([
        'ride_id' => 'required|exists:rides,id',
        'lat'     => 'required|numeric',
        'lng'     => 'required|numeric',
    ]);

    $ride = Ride::find($request->ride_id);

    if ($ride->driver_id !== auth()->id() || $ride->status !== 'accepted') {
        abort(403);
    }

    $ride->update([
        'driver_lat' => $request->lat,
        'driver_lng' => $request->lng,
    ]);

    return response()->json(['status' => 'updated']);
}


}
