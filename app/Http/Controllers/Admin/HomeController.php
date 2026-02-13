<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ride;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $data = [
            'totalRides'     => Ride::withoutGlobalScopes()->count(),
            'pendingRides'   => Ride::withoutGlobalScopes()->where('status', 'pending')->count(),
            'acceptedRides'  => Ride::withoutGlobalScopes()->where('status', 'accepted')->count(),
            'completedRides' => Ride::withoutGlobalScopes()->where('status', 'completed')->count(),
            'cancelledRides' => Ride::withoutGlobalScopes()->where('status', 'cancelled')->count(),

            'totalUsers'     => User::count(),
            'totalDrivers'   => User::whereHas('roles', fn($q) => $q->where('id', 3))->count(),
            'totalCustomers' => User::whereHas('roles', fn($q) => $q->where('id', 2))->count(),
        ];

        return view('home', compact('data'));
    }
}
