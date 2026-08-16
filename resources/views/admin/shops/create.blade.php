@extends('layouts.app')

@push('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
@endpush

@section('content')
<div class="bg-white p-4 rounded shadow-sm" style="max-width: 700px;">
    <h3>Add New Shop</h3>

    <form action="{{ route('admin.shops.store') }}" method="POST" class="mt-4">
        @csrf

        <div class="mb-3">
            <label class="form-label">Shop Name</label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror">
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Owner Name</label>
            <input type="text" name="owner_name" value="{{ old('owner_name') }}" class="form-control @error('owner_name') is-invalid @enderror">
            @error('owner_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row">
            <div class="col-12 col-md-8 mb-3">
                <label class="form-label">Address</label>
                <input type="text" name="address" value="{{ old('address') }}" class="form-control @error('address') is-invalid @enderror">
                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-12 col-md-4 mb-3">
                <label class="form-label">Area</label>
                <input type="text" name="area" value="{{ old('area') }}" class="form-control @error('area') is-invalid @enderror">
                @error('area') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror">
            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>



       <div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input" id="createLoginCheck" name="create_login" value="1"
           onchange="document.getElementById('loginFields').style.display = this.checked ? 'block' : 'none';"
           {{ old('create_login') ? 'checked' : '' }}>
    <label class="form-check-label" for="createLoginCheck">
        Also create a login for this shop's owner
    </label>
</div>

<div id="loginFields" class="mb-3" style="display: {{ old('create_login') ? 'block' : 'none' }};">
    <label class="form-label">Email</label>
    <div class="input-group">
        <input type="text" name="email_prefix" value="{{ old('email_prefix') }}" class="form-control @error('email_prefix') is-invalid @enderror" placeholder="e.g. karachi-store1">
        <span class="input-group-text">@salesconnect.com</span>
        @error('email_prefix') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

        <div class="mb-3">
            <label class="form-label">Shop Location</label>

            <button type="button" id="useCurrentLocation" class="btn btn-outline-primary btn-sm mb-2">
                <i class="bi bi-crosshair"></i> Use My Current Location
            </button>
            <span id="locationLoading" class="text-muted small ms-2" style="display: none;">
                <i class="bi bi-hourglass-split"></i> Getting your location...
            </span>

            <div class="text-muted small mb-2">Or click anywhere on the map to set the pin manually.</div>

            <div id="map" style="height: 350px; border-radius: 6px;" class="@error('latitude') border border-danger @enderror"></div>
            @error('latitude') <div class="text-danger small mt-1">{{ $message }}</div> @enderror

            <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
            <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
            <div class="small text-muted mt-1" id="coordsDisplay">No location selected yet.</div>
        </div>

        <button type="submit" class="btn btn-primary">Create Shop</button>
        <a href="{{ route('admin.shops.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const map = L.map('map').setView([30.3753, 69.3451], 5);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    let marker = null;

    map.on('click', function (e) {
        const { lat, lng } = e.latlng;

        if (marker) {
            marker.setLatLng(e.latlng);
        } else {
            marker = L.marker(e.latlng).addTo(map);
        }

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

                if (marker) {
                    marker.setLatLng(newLatLng);
                } else {
                    marker = L.marker(newLatLng).addTo(map);
                }

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

                let message = 'Could not get your location. ';
                if (error.code === error.PERMISSION_DENIED) {
                    message += 'Please allow location access in your browser.';
                } else if (error.code === error.POSITION_UNAVAILABLE) {
                    message += 'Location information is unavailable.';
                } else {
                    message += 'Please try again.';
                }
                alert(message);
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
            }
        );
    });
</script>
@endpush