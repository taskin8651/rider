@extends('layouts.admin')
@section('content')

<h2 class="text-xl font-semibold mb-4">My Earnings</h2>

<p>Total Earned: ₹ {{ $earned }}</p>
<p>Requested: ₹ {{ $requested }}</p>
<p><b>Available: ₹ {{ $balance }}</b></p>

<form method="POST" action="{{ route('driver.payouts.store') }}" class="mt-4">
    @csrf
    <input type="number" name="amount"
           max="{{ $balance }}"
           required
           class="border px-3 py-2 rounded">
    <button class="ml-2 px-4 py-2 bg-slate-900 text-white rounded">
        Request Payout
    </button>
</form>

@endsection
