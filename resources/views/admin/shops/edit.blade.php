@extends('layouts.app')

@push('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
@endpush

@section('content')
<div class="bg-white p-4 rounded shadow-sm" style="max-width: 700px;">
    <h3>Edit Shop</h3>

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
            <label class="form-label">Shop Location (click on the map to update the pin)</label>
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
</div>

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
</script>
@endsection