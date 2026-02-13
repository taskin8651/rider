@php
$colors = [
    'pending'   => 'bg-yellow-100 text-yellow-800',
    'accepted'  => 'bg-blue-100 text-blue-800',
    'completed' => 'bg-green-100 text-green-800',
    'cancelled' => 'bg-red-100 text-red-800',
];
@endphp

<span class="px-3 py-1 rounded-full text-xs font-semibold
{{ $colors[$status] ?? 'bg-gray-100 text-gray-800' }}">
{{ ucfirst($status) }}
</span>
