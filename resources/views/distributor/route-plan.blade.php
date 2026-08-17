@extends('layouts.app')

@section('content')

    <h3 class="mb-4">Today's Route Plan — {{ ucfirst(now()->format('l')) }}</h3>

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
        </div>
    @endif

    <div class="alert alert-light border small">
        <i class="bi bi-info-circle"></i>
        Stand near the shop and tap <strong>"Check My Location"</strong> — the "Mark Visit" button will unlock once you're close enough.
    </div>

    @forelse ($assignments as $assignment)
        @php $shop = $assignment->shop; $isVisited = $visitedShopIdsToday->contains($assignment->shop_id); @endphp

        @if ($shop)
        <div class="card shadow-sm border-0 mb-3" data-shop-id="{{ $shop->id }}" data-shop-lat="{{ $shop->latitude }}" data-shop-lng="{{ $shop->longitude }}">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div>
                        <span class="badge bg-secondary me-1">{{ $assignment->visit_order }}</span>
                        <strong>{{ $shop->name }}</strong>
                        <div class="text-muted small">{{ $shop->address }}, {{ $shop->area }}</div>
                    </div>
                    @if ($isVisited)
                        <span class="badge bg-success align-self-center">✓ Visited Today</span>
                    @endif
                </div>

                @unless ($isVisited)
                    <div class="mt-3 pt-3 border-top">
                        <button type="button" class="btn btn-outline-primary btn-sm check-location-btn">
                            <i class="bi bi-crosshair"></i> Check My Location
                        </button>

                        <div class="distance-display small text-muted mt-2"></div>

                        <form action="{{ route('distributor.visits.store') }}" method="POST" class="mark-visit-form mt-2" style="display: none;">
                            @csrf
                            <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                            <input type="hidden" name="latitude" class="visit-lat">
                            <input type="hidden" name="longitude" class="visit-lng">
                            <button type="submit" class="btn btn-success btn-sm mark-visit-btn" disabled>
                                <i class="bi bi-check-circle"></i> Mark Visit
                            </button>
                        </form>
                    </div>
                @endunless
            </div>
        </div>
        @endif
    @empty
        <p class="text-muted">No shops assigned for today.</p>
    @endforelse

@endsection

@push('scripts')
<script>
    const ALLOWED_RADIUS = {{ config('visit.radius_meters') }};

    function haversineDistance(lat1, lon1, lat2, lon2) {
        const R = 6371000;
        const toRad = deg => deg * Math.PI / 180;

        const dLat = toRad(lat2 - lat1);
        const dLon = toRad(lon2 - lon1);

        const a = Math.sin(dLat / 2) ** 2 +
                  Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) * Math.sin(dLon / 2) ** 2;

        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

        return R * c;
    }

    document.querySelectorAll('.check-location-btn').forEach(function (button) {
        button.addEventListener('click', function () {
            const card = button.closest('.card');
            const shopLat = parseFloat(card.dataset.shopLat);
            const shopLng = parseFloat(card.dataset.shopLng);
            const distanceDisplay = card.querySelector('.distance-display');
            const form = card.querySelector('.mark-visit-form');
            const markBtn = card.querySelector('.mark-visit-btn');

            if (!navigator.geolocation) {
                distanceDisplay.innerText = 'Geolocation is not supported by your browser.';
                return;
            }

            distanceDisplay.innerText = 'Getting your location...';

            navigator.geolocation.getCurrentPosition(
                function (position) {
                    const myLat = position.coords.latitude;
                    const myLng = position.coords.longitude;

                    const distance = haversineDistance(shopLat, shopLng, myLat, myLng);

                    form.querySelector('.visit-lat').value = myLat;
                    form.querySelector('.visit-lng').value = myLng;
                    form.style.display = 'block';

                    if (distance <= ALLOWED_RADIUS) {
                        distanceDisplay.innerHTML =
                            '<span class="text-success"><i class="bi bi-check-circle"></i> ' +
                            Math.round(distance) + 'm away — within range!</span>';
                        markBtn.disabled = false;
                    } else {
                        distanceDisplay.innerHTML =
                            '<span class="text-danger"><i class="bi bi-x-circle"></i> ' +
                            Math.round(distance) + 'm away — too far (need within ' + ALLOWED_RADIUS + 'm).</span>';
                        markBtn.disabled = true;
                    }
                },
                function (error) {
                    distanceDisplay.innerText = 'Could not get your location. Please allow location access.';
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        });
    });
</script>
@endpush