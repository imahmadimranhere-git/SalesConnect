@extends('layouts.app')

@section('content')

    <h3 class="mb-4">My Dashboard — {{ ucfirst(now()->format('l')) }}</h3>

    <div class="row g-3 mb-4">

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 text-primary rounded p-3 me-3">
                        <i class="bi bi-shop fs-3"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Assigned Shops</div>
                        <div class="fs-4 fw-bold">{{ $totalAssignedShops }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 text-success rounded p-3 me-3">
                        <i class="bi bi-check-circle fs-3"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Visited Today</div>
                        <div class="fs-4 fw-bold">{{ $visitedToday }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 text-warning rounded p-3 me-3">
                        <i class="bi bi-hourglass-split fs-3"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Pending Visits</div>
                        <div class="fs-4 fw-bold">{{ $pendingVisits }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 text-info rounded p-3 me-3">
                        <i class="bi bi-bag-check fs-3"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Orders Today</div>
                        <div class="fs-4 fw-bold">{{ $ordersToday }}</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="bg-white p-3 rounded shadow-sm">
        <h5 class="mb-3">Today's Route</h5>

        @forelse ($todaysAssignments as $assignment)
            @php $isVisited = $visitedShopIdsToday->contains($assignment->shop_id); @endphp

            <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                <div>
                    <span class="badge bg-secondary me-2">{{ $assignment->visit_order }}</span>
                    <strong>{{ $assignment->shop?->name ?? 'Deleted Shop' }}</strong>
                    <div class="text-muted small ms-4">{{ $assignment->shop?->address }}, {{ $assignment->shop?->area }}</div>
                </div>
                <div>
                    @if ($isVisited)
                        <span class="badge bg-success">Visited</span>
                    @else
                        <span class="badge bg-warning text-dark">Pending</span>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-muted">No shops assigned for today.</p>
        @endforelse
    </div>

@endsection