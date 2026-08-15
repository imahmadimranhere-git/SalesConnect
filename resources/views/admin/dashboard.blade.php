@extends('layouts.app')

@section('content')

    <h3 class="mb-4">Admin Dashboard</h3>

    <div class="row g-3">

        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 text-success rounded p-3 me-3">
                        <i class="bi bi-truck fs-3"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Distributors</div>
                        <div class="fs-4 fw-bold">{{ $totalDistributors }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 text-warning rounded p-3 me-3">
                        <i class="bi bi-shop fs-3"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Shops</div>
                        <div class="fs-4 fw-bold">{{ $totalShops }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 text-info rounded p-3 me-3">
                        <i class="bi bi-person-badge fs-3"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Shopkeepers</div>
                        <div class="fs-4 fw-bold">{{ $totalShopkeepers }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 text-primary rounded p-3 me-3">
                        <i class="bi bi-check-circle fs-3"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Visited Today</div>
                        <div class="fs-4 fw-bold">{{ $visitedToday }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-secondary bg-opacity-10 text-secondary rounded p-3 me-3">
                        <i class="bi bi-hourglass-split fs-3"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Pending Visits</div>
                        <div class="fs-4 fw-bold">{{ $pendingVisits }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-danger bg-opacity-10 text-danger rounded p-3 me-3">
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

    <div class="alert alert-light border mt-4">
        <i class="bi bi-info-circle"></i>
        Visits, Orders, and detailed reports will appear here once those modules are built.
    </div>

@endsection