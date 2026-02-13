<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ride;


class RideController extends Controller
{
     // List rides
    public function index()
    {
        $rides = Ride::latest()->get();
        return view('rides.index', compact('rides'));
    }

    // Customer creates ride
   
public function store(Request $request)
{
    // dd($request->all());
    // ✅ Validation
    $request->validate([
        'pickup_location' => 'required|string|max:255',
        'drop_location'   => 'required|string|max:255',
        'fare'            => 'nullable|numeric|min:0',
        'distance_km'     => 'nullable|numeric|min:0',
        'duration_min'    => 'nullable|numeric|min:0',
    ]);

    // ✅ Create Ride
    Ride::create([
        'customer_id'     => auth()->id(),
        'pickup_location' => $request->pickup_location,
        'drop_location'   => $request->drop_location,
        'fare'            => $request->fare ?? 0,
        'distance_km'     => $request->distance_km ?? null,
        'duration_min'    => $request->duration_min ?? null,
        'status'          => 'pending',
    ]);
    // dd([
    //     'customer_id'     => auth()->id(),
    //     'pickup_location' => $request->pickup_location,
    //     'drop_location'   => $request->drop_location,
    //     'fare'            => $request->fare ?? 0,
    //     'distance_km'     => $request->distance_km ?? null,
    //     'duration_min'    => $request->duration_min ?? null,
    //     'status'          => 'pending',
    // ]);

    return redirect()->back()
        ->with('success', 'Ride booked successfully');
}

    public function cancel(Ride $ride)
{
    // Sirf apni ride
    if ($ride->customer_id !== auth()->id()) {
        abort(403);
    }

    // Completed ride cancel nahi ho sakti
    if ($ride->status === 'completed') {
        return back()->with('error', 'Completed ride cannot be cancelled');
    }

    $ride->update([
        'status' => 'cancelled'
    ]);

    return back()->with('success', 'Ride cancelled successfully');
}
public function driverLocation(Ride $ride)
{
    $user = auth()->user();

    // 🔐 Allow Customer (own ride) OR Admin
    if (!$user->is_admin && $ride->customer_id !== $user->id) {
        abort(403);
    }

    // Agar ride accepted nahi hai to tracking band
    if ($ride->status !== 'accepted') {
        return response()->json([
            'lat'    => null,
            'lng'    => null,
            'status' => $ride->status
        ]);
    }

    return response()->json([
        'lat'    => $ride->driver_lat,
        'lng'    => $ride->driver_lng,
        'status' => $ride->status
    ]);
}



}
