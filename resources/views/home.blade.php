@extends('layouts.admin')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">

    {{-- TOTAL RIDES --}}
    <div class="bg-white p-6 rounded shadow">
        <h3 class="text-gray-500">Total Rides</h3>
        <p class="text-3xl font-bold">{{ $data['totalRides'] }}</p>
    </div>

    <div class="bg-yellow-100 p-6 rounded shadow">
        <h3 class="text-gray-600">Pending</h3>
        <p class="text-3xl font-bold">{{ $data['pendingRides'] }}</p>
    </div>

    <div class="bg-blue-100 p-6 rounded shadow">
        <h3 class="text-gray-600">Accepted</h3>
        <p class="text-3xl font-bold">{{ $data['acceptedRides'] }}</p>
    </div>

    <div class="bg-green-100 p-6 rounded shadow">
        <h3 class="text-gray-600">Completed</h3>
        <p class="text-3xl font-bold">{{ $data['completedRides'] }}</p>
    </div>

    <div class="bg-red-100 p-6 rounded shadow">
        <h3 class="text-gray-600">Cancelled</h3>
        <p class="text-3xl font-bold">{{ $data['cancelledRides'] }}</p>
    </div>

    {{-- USERS --}}
    <div class="bg-slate-100 p-6 rounded shadow">
        <h3 class="text-gray-600">Total Users</h3>
        <p class="text-3xl font-bold">{{ $data['totalUsers'] }}</p>
    </div>

    <div class="bg-indigo-100 p-6 rounded shadow">
        <h3 class="text-gray-600">Drivers</h3>
        <p class="text-3xl font-bold">{{ $data['totalDrivers'] }}</p>
    </div>

    <div class="bg-purple-100 p-6 rounded shadow">
        <h3 class="text-gray-600">Customers</h3>
        <p class="text-3xl font-bold">{{ $data['totalCustomers'] }}</p>
    </div>

</div>
@endsection
