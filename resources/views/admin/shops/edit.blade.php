@extends('layouts.app')

@push('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
@endpush

@section('content')
<div class="bg-white p-4 rounded shadow-sm" style="max-width: 700px;">
    <h3>Edit Shop</h3>

    {{-- ============ MAIN SHOP EDIT FORM ============ --}}
    <form action="{{ route('admin.shops.update', $shop) }}" method="POST" class="mt-4">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Shop Name</label>
            <input type="text" name="name" value="{{ old('name', $shop->name) }}" class="form-control @error('name') is-invalid @enderror">
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Owner Name</label>
            <input type="text" name="owner_name" value="{{ old('owner_name', $shop->owner_name) }}" class="form-control @error('owner_name') is-invalid @enderror">
            @error('owner_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row">
            <div class="col-12 col-md-8 mb-3">
                <label class="form-label">Address</label>
                <input type="text" name="address" value="{{ old('address', $shop->address) }}" class="form-control @error('address') is-invalid @enderror">
                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-12 col-md-4 mb-3">
                <label class="form-label">Area</label>
                <input type="text" name="area" value="{{ old('area', $shop->area) }}" class="form-control @error('area') is-invalid @enderror">
                @error('area') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $shop->phone) }}" class="form-control @error('phone') is-invalid @enderror">
            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Shop Location</label>

            <button type="button" id="useCurrentLocation" class="btn btn-outline-primary btn-sm mb-2">
                <i class="bi bi-crosshair"></i> Use My Current Location
            </button>
            <span id="locationLoading" class="text-muted small ms-2" style="display: none;">
                <i class="bi bi-hourglass-split"></i> Getting your location...
            </span>

            <div class="text-muted small mb-2">Or click anywhere on the map to update the pin manually.</div>

            <div id="map" style="height: 350px; border-radius: 6px;"></div>

            <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $shop->latitude) }}">
            <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $shop->longitude) }}">
            <div class="small text-muted mt-1" id="coordsDisplay">
                Current: {{ $shop->latitude }}, {{ $shop->longitude }}
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Update Shop</button>
        <a href="{{ route('admin.shops.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </form>

    {{-- ============ SHOPKEEPER LOGIN — ALAG, INDEPENDENT SECTION ============ --}}
    <div class="mb-3 p-3 bg-light rounded mt-4">
        <label class="form-label fw-semibold">Shopkeeper Login</label>

        @if ($shop->shopkeeper)
            <div class="small text-muted mb-2">
                Email: <strong>{{ $shop->shopkeeper->email }}</strong> —
                Status:
                <span class="badge {{ $shop->shopkeeper->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                    {{ ucfirst($shop->shopkeeper->status) }}
                </span>
            </div>

            <form action="{{ route('admin.shops.reset-password', $shop) }}" method="POST" onsubmit="return confirm('Generate a new password for this login?')">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-sm btn-warning">
                    <i class="bi bi-key"></i> Reset Password
                </button>
            </form>
        @else
            <div class="small text-muted mb-2">This shop does not have a login yet.</div>

            <form action="{{ route('admin.shops.add-login', $shop) }}" method="POST" class="d-flex gap-2 flex-wrap">
                @csrf
                <div class="input-group" style="max-width: 350px;">
                    <input type="text" name="email_prefix" class="form-control form-control-sm" placeholder="e.g. karachi-store1">
                    <span class="input-group-text">@salesconnect.com</span>
                </div>
                <button type="submit" class="btn btn-sm btn-primary">Create Login</button>
            </form>
        @endif
    </div>

</div>

@push('scripts')
<script>
    const initialLat = {{ $shop->latitude }};
    const initialLng = {{ $shop->longitude }};

    const map = L.map('map').setView([initialLat, initialLng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    let marker = L.marker([initialLat, initialLng]).addTo(map);

    map.on('click', function (e) {
        const { lat, lng } = e.latlng;
        marker.setLatLng(e.latlng);

        document.getElementById('latitude').value = lat.toFixed(7);
        document.getElementById('longitude').value = lng.toFixed(7);
        document.getElementById('coordsDisplay').innerText =
            'Selected: ' + lat.toFixed(6) + ', ' + lng.toFixed(6);
    });

    document.getElementById('useCurrentLocation').addEventListener('click', function () {
        const loadingText = document.getElementById('locationLoading');

        if (!navigator.geolocation) {
            alert('Geolocation is not supported by your browser.');
            return;
        }

        loadingText.style.display = 'inline';

        navigator.geolocation.getCurrentPosition(
            function (position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                const newLatLng = { lat: lat, lng: lng };

                marker.setLatLng(newLatLng);
                map.setView(newLatLng, 17);

                document.getElementById('latitude').value = lat.toFixed(7);
                document.getElementById('longitude').value = lng.toFixed(7);
                document.getElementById('coordsDisplay').innerText =
                    'Selected (GPS): ' + lat.toFixed(6) + ', ' + lng.toFixed(6) +
                    ' (accuracy: ' + Math.round(position.coords.accuracy) + 'm)';

                loadingText.style.display = 'none';
            },
            function (error) {
                loadingText.style.display = 'none';
                alert('Could not get your location. Please allow location access or click on the map.');
            },
            { enableHighAccuracy: true, timeout: 10000 }
        );
    });
</script>
@endpush
@endsection