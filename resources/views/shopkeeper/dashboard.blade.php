@extends('layouts.app')

@section('content')

    <div class="bg-white p-3 rounded shadow-sm mb-4 d-flex align-items-center gap-3">
        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
            <i class="bi bi-shop fs-3"></i>
        </div>
        <div>
            <h5 class="mb-0">{{ $shop?->name ?? 'N/A' }}</h5>
            <div class="text-muted small">
                Distributor: {{ $lastVisit?->distributor?->name ?? 'Not assigned yet' }}
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 text-info rounded p-3 me-3">
                        <i class="bi bi-bag-check fs-3"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Orders This Month</div>
                        <div class="fs-4 fw-bold">{{ $totalOrdersThisMonth }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 text-warning rounded p-3 me-3">
                        <i class="bi bi-hourglass-split fs-3"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Pending Orders</div>
                        <div class="fs-4 fw-bold">{{ $pendingOrders }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 text-success rounded p-3 me-3">
                        <i class="bi bi-cash-stack fs-3"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Amount (This Month)</div>
                        <div class="fs-4 fw-bold">Rs. {{ number_format($totalAmountThisMonth, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($lastVisit)
        <div class="alert alert-light border small">
            <i class="bi bi-geo-alt"></i>
            Last visited by <strong>{{ $lastVisit->distributor?->name }}</strong> on {{ $lastVisit->visited_at->format('d M Y, h:i A') }}
        </div>
    @endif

@endsection