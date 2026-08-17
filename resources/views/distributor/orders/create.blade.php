@extends('layouts.app')

@section('content')
<div class="bg-white p-4 rounded shadow-sm">
    <h3>Create New Order</h3>

    <form action="{{ route('distributor.orders.store') }}" method="POST" class="mt-4" id="orderForm">
        @csrf

        <div class="mb-3" style="max-width: 400px;">
            <label class="form-label">Shop</label>
            <select name="shop_id" class="form-select @error('shop_id') is-invalid @enderror" required>
                <option value="">Select a shop</option>
                @foreach ($shops as $shop)
                    <option value="{{ $shop->id }}">{{ $shop->name }} ({{ $shop->area }})</option>
                @endforeach
            </select>
            @error('shop_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <hr>

        <h6>Products</h6>
        <div id="productRows"></div>

        <button type="button" id="addProductRow" class="btn btn-sm btn-outline-primary mt-2">
            <i class="bi bi-plus-lg"></i> Add Product
        </button>

        <div class="mt-4 text-end">
            <h5>Total: Rs. <span id="orderTotal">0.00</span></h5>
        </div>

        <button type="submit" class="btn btn-primary">Create Order</button>
        <a href="{{ route('distributor.orders.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </form>
</div>

@push('scripts')
<script>
    const products = @json($products);
    let rowCount = 0;

    function addProductRow() {
        const container = document.getElementById('productRows');
        const rowId = rowCount++;

        const row = document.createElement('div');
        row.className = 'row g-2 align-items-center mb-2 product-row';
        row.dataset.rowId = rowId;

        let options = '<option value="">Select product</option>';
        products.forEach(p => {
            options += `<option value="${p.id}" data-price="${p.price}" data-stock="${p.stock_quantity}">${p.name} (Rs. ${p.price}, Stock: ${p.stock_quantity})</option>`;
        });

        row.innerHTML = `
            <div class="col-12 col-md-5">
                <select name="products[${rowId}][id]" class="form-select form-select-sm product-select" required>
                    ${options}
                </select>
            </div>
            <div class="col-6 col-md-2">
                <input type="number" name="products[${rowId}][quantity]" class="form-control form-control-sm product-qty" min="1" value="1" required>
            </div>
            <div class="col-6 col-md-3">
                <span class="small text-muted row-subtotal">Rs. 0.00</span>
            </div>
            <div class="col-12 col-md-2">
                <button type="button" class="btn btn-sm btn-outline-danger remove-row">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;

        container.appendChild(row);

        row.querySelector('.product-select').addEventListener('change', updateOrderTotal);
        row.querySelector('.product-qty').addEventListener('input', updateOrderTotal);
        row.querySelector('.remove-row').addEventListener('click', function () {
            row.remove();
            updateOrderTotal();
        });
    }

    function updateOrderTotal() {
        let total = 0;

        document.querySelectorAll('.product-row').forEach(row => {
            const select = row.querySelector('.product-select');
            const qtyInput = row.querySelector('.product-qty');
            const subtotalSpan = row.querySelector('.row-subtotal');

            const selectedOption = select.options[select.selectedIndex];
            const price = parseFloat(selectedOption?.dataset.price || 0);
            const qty = parseInt(qtyInput.value || 0);

            const subtotal = price * qty;
            subtotalSpan.innerText = 'Rs. ' + subtotal.toFixed(2);

            total += subtotal;
        });

        document.getElementById('orderTotal').innerText = total.toFixed(2);
    }

    document.getElementById('addProductRow').addEventListener('click', addProductRow);

    // Start with one product row visible.
    addProductRow();
</script>
@endpush
@endsection