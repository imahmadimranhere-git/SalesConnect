@extends('layouts.app')

@push('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
@endpush

@section('content')

    <h3 class="mb-4">Route History</h3>

    {{-- Filter --}}
    <form method="GET" class="bg-white p-3 rounded shadow-sm mb-4">
        <div class="row g-2 align-items-end">

            <div class="col-12 col-md-4">
                <label class="form-label small">Distributor</label>

                <select name="distributor_id" class="form-select form-select-sm" required>
                    <option value="">Select a distributor</option>

                    @foreach ($distributors as $distributor)
                        <option
                            value="{{ $distributor->id }}"
                            {{ request('distributor_id') == $distributor->id ? 'selected' : '' }}
                        >
                            {{ $distributor->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-md-4">
                <label class="form-label small">Date</label>

                <input
                    type="date"
                    name="date"
                    value="{{ $selectedDate }}"
                    class="form-control form-control-sm"
                >
            </div>

            <div class="col-12 col-md-2">
                <button type="submit" class="btn btn-sm btn-primary w-100">
                    View Route
                </button>
            </div>

        </div>
    </form>


    @if ($selectedDistributor)

        <div class="bg-white p-3 rounded shadow-sm mb-3">
            <strong>{{ $selectedDistributor->name }}</strong>
            —
            {{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }}
            —
            <span class="text-muted small">
                {{ $visits->count() }} visit(s) recorded
            </span>
        </div>


        @if ($visits->isNotEmpty())

            <div
                id="map"
                style="height: 450px; border-radius: 6px;"
                class="mb-3"
            ></div>


            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle bg-white">

                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Shop</th>
                            <th>Time</th>
                            <th>Distance from Shop</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($visits as $index => $visit)
                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td>
                                    {{ $visit->shop?->name ?? 'Deleted Shop' }}
                                </td>

                                <td>
                                    {{ $visit->visited_at->format('h:i A') }}
                                </td>

                                <td>
                                    {{ number_format($visit->distance_meters, 0) }}m
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

        @else

            <p class="text-muted">
                No visits recorded for this distributor on this date.
            </p>

        @endif

    @else

        <p class="text-muted">
            Select a distributor and date to view their route history.
        </p>

    @endif

@endsection


@push('scripts')

<script>
    @if ($selectedDistributor && $visits->isNotEmpty())

        // Prepare visit data in PHP first.
        @php
            $routeVisits = $visits->map(function ($visit) {
                return [
                    'lat' => (float) $visit->latitude,
                    'lng' => (float) $visit->longitude,
                    'shop' => $visit->shop?->name ?? 'Deleted Shop',
                    'time' => $visit->visited_at->format('h:i A'),
                ];
            })->values();
        @endphp

        const visits = @json($routeVisits);

        const map = L.map('map');

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const latLngs = [];

        visits.forEach((visit, index) => {

            const marker = L.marker([
                visit.lat,
                visit.lng
            ]).addTo(map);

            marker.bindPopup(
                `<strong>${index + 1}. ${visit.shop}</strong><br>${visit.time}`
            );

            latLngs.push([
                visit.lat,
                visit.lng
            ]);
        });


        // Draw a line connecting the visits in order.
        if (latLngs.length > 1) {

            L.polyline(latLngs, {
                color: '#0d6efd',
                weight: 3,
                dashArray: '6, 6'
            }).addTo(map);

        }


        // Zoom the map to fit all markers.
        if (latLngs.length > 0) {

            map.fitBounds(latLngs, {
                padding: [30, 30]
            });

        }

    @endif
</script>

@endpush