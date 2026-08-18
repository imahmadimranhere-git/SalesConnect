@extends('layouts.app')

@section('content')

    <h3 class="mb-4">My Assigned Shops</h3>

    <div class="row g-3">
        @forelse ($assignments as $shopId => $shopAssignments)
            @php $shop = $shopAssignments->first()->shop; @endphp

            @if ($shop)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h6 class="mb-1">{{ $shop->name }}</h6>
                        <div class="text-muted small mb-2">{{ $shop->address }}, {{ $shop->area }}</div>
                        <div class="text-muted small mb-2"><i class="bi bi-telephone"></i> {{ $shop->phone }}</div>

                        <div class="mb-2">
                            @foreach ($shopAssignments->sortBy('day_of_week') as $assignment)
                                <span class="badge bg-secondary bg-opacity-10 text-secondary me-1">
                                    {{ ucfirst($assignment->day_of_week) }}
                                </span>
                            @endforeach
                        </div>

                        <a href="https://www.google.com/maps?q={{ $shop->latitude }},{{ $shop->longitude }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-geo-alt"></i> View on Map
                        </a>
                    </div>
                </div>
            </div>
            @endif
        @empty
            <p class="text-muted">No shops assigned to you yet.</p>
        @endforelse
    </div>

@endsection