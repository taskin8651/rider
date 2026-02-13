@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto space-y-10">

    {{-- ===============================
        PENDING RIDES
    ================================ --}}
    <div class="bg-white shadow rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold text-gray-800">
                Pending Rides
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left">ID</th>
                        <th class="px-6 py-3 text-left">Pickup</th>
                        <th class="px-6 py-3 text-left">Drop</th>
                        <th class="px-6 py-3 text-left">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                @forelse($pendingRides as $ride)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-medium">
                            #{{ $ride->id }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $ride->pickup_location }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $ride->drop_location }}
                        </td>
                        <td class="px-6 py-4">
                            <form method="POST"
                                  action="{{ route('driver.rides.accept', $ride->id) }}">
                                @csrf
                                <button type="submit"
                                    class="px-4 py-2 text-xs font-semibold
                                           bg-blue-600 text-white rounded-lg
                                           hover:bg-blue-700 transition">
                                    Accept Ride
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4"
                            class="px-6 py-6 text-center text-gray-500">
                            No pending rides available
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>


    {{-- ===============================
        MY RIDES
    ================================ --}}
    <div class="bg-white shadow rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold text-gray-800">
                My Rides
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left">ID</th>
                        <th class="px-6 py-3 text-left">Pickup</th>
                        <th class="px-6 py-3 text-left">Drop</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                @forelse($myRides as $ride)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-medium">
                            #{{ $ride->id }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $ride->pickup_location }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $ride->drop_location }}
                        </td>

                        {{-- STATUS BADGE --}}
                        <td class="px-6 py-4">
                            @php
                                $colors = [
                                    'pending'   => 'bg-yellow-100 text-yellow-800',
                                    'accepted'  => 'bg-blue-100 text-blue-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                ];
                            @endphp

                            <span id="ride-status-{{ $ride->id }}"
                                class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $colors[$ride->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($ride->status) }}
                            </span>
                        </td>

                        {{-- ACTION BUTTONS --}}
                        <td class="px-6 py-4 space-x-2">

                            @if($ride->status === 'accepted')
                                {{-- Complete --}}
                                <form method="POST"
                                      action="{{ route('driver.rides.complete', $ride->id) }}"
                                      class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="px-3 py-1 text-xs font-semibold
                                               bg-green-600 text-white rounded-lg
                                               hover:bg-green-700 transition">
                                        Complete
                                    </button>
                                </form>

                                {{-- Cancel --}}
                                <form method="POST"
                                      action="{{ route('driver.rides.cancel', $ride->id) }}"
                                      class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="px-3 py-1 text-xs font-semibold
                                               bg-red-600 text-white rounded-lg
                                               hover:bg-red-700 transition">
                                        Cancel
                                    </button>
                                </form>
                            @else
                                <span class="text-gray-400 text-xs">—</span>
                            @endif

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5"
                            class="px-6 py-6 text-center text-gray-500">
                            You have no rides yet
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>


{{-- ===============================
    DRIVER LIVE TRACKING SAFE JS
================================ --}}
<script>
let driverMarker = null;

function startDriverTracking(rideId) {

    if (typeof map === "undefined") return;

    setInterval(() => {

        fetch(`/rides/${rideId}/driver-location`)
            .then(res => res.json())
            .then(data => {

                if (!data.lat || !data.lng) return;

                const pos = {
                    lat: parseFloat(data.lat),
                    lng: parseFloat(data.lng)
                };

                if (!driverMarker) {
                    driverMarker = new google.maps.Marker({
                        position: pos,
                        map: map,
                        icon: {
                            url: "https://maps.google.com/mapfiles/kml/shapes/cabs.png",
                            scaledSize: new google.maps.Size(40,40)
                        }
                    });
                } else {
                    driverMarker.setPosition(pos);
                }

                if (data.status === 'completed' || data.status === 'cancelled') {
                    location.reload();
                }

            });

    }, 5000);
}
</script>

<script>

@foreach($myRides as $ride)
@if($ride->status === 'accepted')

setInterval(() => {

    if (!navigator.geolocation) {
        console.log("Geolocation not supported");
        return;
    }

    navigator.geolocation.getCurrentPosition(position => {

        fetch("{{ route('driver.location.update') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                ride_id: {{ $ride->id }},
                lat: position.coords.latitude,
                lng: position.coords.longitude
            })
        })
        .then(res => res.json())
        .then(data => {
            console.log("Location updated", data);
        });

    });

}, 5000);

@endif
@endforeach

</script>


@endsection
