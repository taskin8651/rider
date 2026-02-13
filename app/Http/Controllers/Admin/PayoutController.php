<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PayoutRequest;

class PayoutController extends Controller
{
    // Admin sees all payout requests
    public function index()
    {
        $payouts = PayoutRequest::with('driver')
            ->latest()->get();

        return view('admin.payouts.index', compact('payouts'));
    }

    // Approve payout
    public function approve(PayoutRequest $payout)
    {
        $payout->update([
            'status' => 'approved'
        ]);

        return back()->with('success', 'Payout approved');
    }

    // Reject payout
    public function reject(PayoutRequest $payout)
    {
        $payout->update([
            'status' => 'rejected'
        ]);

        return back()->with('success', 'Payout rejected');
    }
}
