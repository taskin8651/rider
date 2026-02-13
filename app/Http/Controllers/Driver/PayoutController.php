<?php
namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\PayoutRequest;
use App\Models\Ride;
use Illuminate\Http\Request;

class PayoutController extends Controller
{
    // Driver payout page
    public function index()
    {
        $driverId = auth()->id();

        $earned = Ride::where('driver_id', $driverId)
            ->where('status', 'completed')
            ->sum('driver_earning');

        $requested = PayoutRequest::where('driver_id', $driverId)
            ->whereIn('status', ['pending','approved'])
            ->sum('amount');

        $balance = $earned - $requested;

        $payouts = PayoutRequest::where('driver_id', $driverId)
            ->latest()->get();

        return view('driver.payouts.index', compact(
            'earned', 'requested', 'balance', 'payouts'
        ));
    }

    // Store payout request
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1'
        ]);

        PayoutRequest::create([
            'driver_id' => auth()->id(),
            'amount'    => $request->amount,
            'status'    => 'pending',
        ]);

        return redirect()->back()
            ->with('success', 'Payout request submitted');
    }
}
