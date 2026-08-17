@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Route Assignment — {{ $distributor->name }}</h3>
        <a href="{{ route('admin.distributors.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back to Distributors
        </a>
    </div>

    {{-- Add New Assignment Form --}}
    <div class="bg-white p-3 rounded shadow-sm mb-4">
        <h6>Assign a Shop</h6>
        <form action="{{ route('admin.distributors.assignments.store', $distributor) }}" method="POST" class="row g-2 align-items-end">
            @csrf

            <div class="col-12 col-md-4">
                <label class="form-label small">Shop</label>
                <select name="shop_id" class="form-select form-select-sm @error('shop_id') is-invalid @enderror">
                    <option value="">Select a shop</option>
                    @foreach ($shops as $shop)
                        <option value="{{ $shop->id }}">{{ $shop->name }} ({{ $shop->area }})</option>
                    @endforeach
                </select>
                @error('shop_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12 col-md-3">
                <label class="form-label small">Day</label>
                <select name="day_of_week" class="form-select form-select-sm @error('day_of_week') is-invalid @enderror">
                    @foreach ($days as $day)
                        <option value="{{ $day }}">{{ ucfirst($day) }}</option>
                    @endforeach
                </select>
                @error('day_of_week') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-6 col-md-2">
                <label class="form-label small">Visit Order</label>
                <input type="number" name="visit_order" min="1" value="1" class="form-control form-control-sm @error('visit_order') is-invalid @enderror">
                @error('visit_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-6 col-md-3">
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    <i class="bi bi-plus-lg"></i> Add to Route
                </button>
            </div>
        </form>
    </div>

    {{-- Day-wise Assignments --}}
    <div class="row g-3">
        @foreach ($days as $day)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white fw-semibold">{{ ucfirst($day) }}</div>
                    <ul class="list-group list-group-flush">
                        @forelse ($assignments->get($day, collect()) as $assignment)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>
                                    <span class="badge bg-secondary me-1">{{ $assignment->visit_order }}</span>
                                    {{ $assignment->shop?->name ?? 'Deleted Shop' }}
                                </span>
                                <form action="{{ route('admin.assignments.destroy', $assignment) }}" method="POST" onsubmit="return confirm('Remove this assignment?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </form>
                            </li>
                        @empty
                            <li class="list-group-item text-muted small">No shops assigned.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        @endforeach
    </div>

@endsection