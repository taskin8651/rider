<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ride;
use App\Models\Setting;

class RideController extends Controller
{
    /* ==========================
       LIST RIDES
    =========================== */
    public function index()
    {
        $rides = Ride::forUser(auth()->user())
                    ->latest()
                    ->get();

        return view('rides.index', compact('rides'));
    }


    /* ==========================
       CUSTOMER BOOK RIDE
    =========================== */
    public function store(Request $request)
    {
        $request->validate([
            'pickup_location' => 'required|string|max:255',
            'drop_location'   => 'required|string|max:255',
            'fare'            => 'nullable|numeric|min:0',
            'distance_km'     => 'nullable|numeric|min:0',
            'duration_min'    => 'nullable|numeric|min:0',
        ]);

        Ride::create([
            'customer_id'     => auth()->id(),
            'pickup_location' => $request->pickup_location,
            'drop_location'   => $request->drop_location,
            'fare'            => $request->fare ?? 0,
            'status'          => 'pending',
        ]);

        return back()->with('success', 'Ride booked successfully');
    }


    /* ==========================
       CANCEL RIDE (CUSTOMER)
    =========================== */
    public function cancel(Ride $ride)
    {
        if ($ride->customer_id !== auth()->id()) {
            abort(403);
        }

        if ($ride->status === 'completed') {
            return back()->with('error', 'Completed ride cannot be cancelled');
        }

        $ride->update([
            'status' => 'cancelled'
        ]);

        return back()->with('success', 'Ride cancelled successfully');
    }


    /* ==========================
       DRIVER ACCEPT RIDE
    =========================== */
    public function accept(Ride $ride)
    {
        if ($ride->status !== 'pending') {
            return back()->with('error', 'Ride already accepted');
        }

        $ride->update([
            'driver_id' => auth()->id(),
            'status'    => 'accepted',
        ]);

        return back()->with('success', 'Ride accepted');
    }


    /* ==========================
       DRIVER COMPLETE RIDE
    =========================== */
    public function complete(Ride $ride)
    {
        if ($ride->driver_id !== auth()->id()) {
            abort(403);
        }

        if ($ride->status !== 'accepted') {
            return back()->with('error', 'Ride cannot be completed');
        }

        $fare = $ride->fare > 0 ? $ride->fare : rand(80, 200);

        $commissionPercent = Setting::get(
            'admin_commission_percentage',
            20
        );

        $adminCommission = round(($fare * $commissionPercent) / 100, 2);
        $driverEarning   = round($fare - $adminCommission, 2);

        $ride->update([
            'status'           => 'completed',
            'fare'             => $fare,
            'admin_commission' => $adminCommission,
            'driver_earning'   => $driverEarning,
        ]);

        return back()->with('success', 'Ride completed');
    }


    /* ==========================
       DRIVER LOCATION UPDATE
    =========================== */
    public function updateLocation(Request $request)
    {
        $request->validate([
            'ride_id' => 'required|exists:rides,id',
            'lat'     => 'required|numeric',
            'lng'     => 'required|numeric',
        ]);

        $ride = Ride::find($request->ride_id);

        if ($ride->driver_id !== auth()->id()) {
            abort(403);
        }

        $ride->update([
            'driver_lat' => $request->lat,
            'driver_lng' => $request->lng,
        ]);

        return response()->json(['status' => 'updated']);
    }


    /* ==========================
       CUSTOMER + ADMIN TRACKING
    =========================== */
    public function driverLocation(Ride $ride)
    {
        $user = auth()->user();

        if (
            !$user->is_admin &&
            $ride->customer_id !== $user->id
        ) {
            abort(403);
        }

        return response()->json([
            'lat'    => $ride->driver_lat,
            'lng'    => $ride->driver_lng,
            'status' => $ride->status,
        ]);
    }
}
