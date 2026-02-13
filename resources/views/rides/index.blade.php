@extends('layouts.admin')
@section('content')

<div class="max-w-6xl mx-auto space-y-10">

    {{-- ================= BOOK RIDE ================= --}}
    @can('book_ride')
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-2">

            {{-- LEFT --}}
            <div class="p-6 md:p-8 space-y-5">
                <h2 class="text-xl font-semibold">Book a Ride</h2>

                <form method="POST" action="{{ route('rides.store') }}">
                    @csrf

                    <input name="pickup_location"
                           required placeholder="Pickup location"
                           class="w-full rounded-xl border px-4 py-3 mb-4">

                    <input name="drop_location"
                           required placeholder="Drop location"
                           class="w-full rounded-xl border px-4 py-3 mb-4">

                    <button type="submit"
                            class="w-full bg-black text-white py-3 rounded-xl">
                        Confirm Ride
                    </button>
                </form>
            </div>

            {{-- RIGHT MAP --}}
            <div class="relative h-[400px]">
                <div id="etaBox"
                     class="hidden absolute top-4 left-4 bg-white px-4 py-2 rounded-lg shadow text-sm z-10">
                </div>
                <div id="map" class="absolute inset-0 rounded-2xl"></div>
            </div>

        </div>
    </div>
    @endcan


    {{-- ================= MY RIDES ================= --}}
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold text-gray-900">My Rides</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3">Ride</th>
                        <th class="px-6 py-3">Pickup</th>
                        <th class="px-6 py-3">Drop</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                @foreach($rides as $ride)
                    <tr>
                        <td class="px-6 py-4 font-medium">#{{ $ride->id }}</td>
                        <td class="px-6 py-4">{{ $ride->pickup_location }}</td>
                        <td class="px-6 py-4">{{ $ride->drop_location }}</td>
                        <td class="px-6 py-4">{{ ucfirst($ride->status) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>


{{-- ================= GOOGLE MAP ================= --}}
<script>
let map;
let driverMarker = null;
let pickupLatLng = null;
let directionsService;
let trackingIntervals = {};

/* ================= LOAD MAP AFTER PAGE READY ================= */
document.addEventListener("DOMContentLoaded", function () {

    const script = document.createElement("script");
    script.src =
        "https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}";
    script.async = true;
    script.defer = true;
    script.onload = initMap;

    document.head.appendChild(script);
});


/* ================= INIT MAP ================= */
function initMap() {

    map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: 28.6139, lng: 77.2090 },
        zoom: 13,
    });

    directionsService = new google.maps.DirectionsService();

    @foreach($rides as $ride)
        @if($ride->status === 'accepted')
            startDriverTracking({{ $ride->id }}, @json($ride->pickup_location));
        @endif
    @endforeach
}


/* ================= DRIVER TRACKING ================= */
function startDriverTracking(rideId, pickupAddress) {

    if (trackingIntervals[rideId]) return;

    const geocoder = new google.maps.Geocoder();

    geocoder.geocode({ address: pickupAddress }, function(results, status) {
        if (status === "OK") {
            pickupLatLng = results[0].geometry.location;
        }
    });

    trackingIntervals[rideId] = setInterval(() => {

        fetch(`/rides/${rideId}/driver-location`)
            .then(res => res.json())
            .then(data => {

                if (!data.lat || !data.lng) return;

                const driverPos = {
                    lat: parseFloat(data.lat),
                    lng: parseFloat(data.lng)
                };

                if (!driverMarker) {
                    driverMarker = new google.maps.Marker({
                        position: driverPos,
                        map: map,
                        icon: {
                            url: "https://maps.google.com/mapfiles/kml/shapes/cabs.png",
                            scaledSize: new google.maps.Size(40,40)
                        }
                    });
                } else {
                    driverMarker.setPosition(driverPos);
                }

                map.setCenter(driverPos);
                calculateETA(driverPos);

                if (data.status === 'completed' || data.status === 'cancelled') {
                    clearInterval(trackingIntervals[rideId]);
                    delete trackingIntervals[rideId];
                }

            })
            .catch(err => console.error("Tracking error:", err));

    }, 5000);
}


/* ================= ETA CALCULATION ================= */
function calculateETA(driverPos) {

    if (!pickupLatLng) return;

    directionsService.route({
        origin: driverPos,
        destination: pickupLatLng,
        travelMode: "DRIVING"
    }, function(response, status) {

        if (status !== "OK") return;

        const leg = response.routes[0].legs[0];
        const minutes = Math.round(leg.duration.value / 60);
        const km = (leg.distance.value / 1000).toFixed(1);

        const etaBox = document.getElementById("etaBox");
        etaBox.innerHTML =
            `🚕 Driver arriving in <b>${minutes} min</b> (${km} km away)`;
        etaBox.classList.remove("hidden");
    });
}
</script>

@endsection
