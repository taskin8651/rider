@extends('layouts.admin')
@section('content')

<h2 class="text-xl font-semibold mb-4">Payout Requests</h2>

<table border="1" cellpadding="10">
<tr>
    <th>Driver</th>
    <th>Amount</th>
    <th>Status</th>
    <th>Action</th>
</tr>

@foreach($payouts as $p)
<tr>
    <td>{{ $p->driver->name }}</td>
    <td>₹ {{ $p->amount }}</td>
    <td>{{ ucfirst($p->status) }}</td>
    <td>
        @if($p->status === 'pending')
        <form method="POST"
              action="{{ route('admin.payouts.approve',$p->id) }}"
              style="display:inline">
            @csrf
            <button>Approve</button>
        </form>

        <form method="POST"
              action="{{ route('admin.payouts.reject',$p->id) }}"
              style="display:inline">
            @csrf
            <button>Reject</button>
        </form>
        @endif
    </td>
</tr>
@endforeach
</table>

@endsection
