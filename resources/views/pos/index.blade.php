@extends('layouts.pos')

@section('content')
<div class="row">
    <!-- Products List -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <input type="text" id="search" class="form-control" placeholder="Search products...">
                    </div>
                    <div class="col">
                        <select class="form-control" id="category-filter">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}">{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row" id="products-grid">
                    @foreach($products as $product)
                    <div class="mb-3 col-md-3">
                        <div class="card product-card" data-id="{{ $product->id }}">
                            <div class="card-body">
                                <h6>{{ $product->name }}</h6>
                                <p class="mb-0">Price: ${{ number_format($product->price, 2) }}</p>
                                <p class="mb-0">Stock: {{ $product->stock }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Cart -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Current Sale</h5>
            </div>
            <div class="card-body">
                <div id="cart-items">
                    <!-- Cart items will be displayed here -->
                </div>
                <div class="mt-3">
                    <h5>Total: $<span id="total-amount">0.00</span></h5>
                </div>
                <button class="mt-3 btn btn-primary w-100" id="checkout-btn">Checkout</button>
            </div>
        </div>
    </div>
</div>

<!-- Checkout Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Checkout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="checkout-form">
                    <div class="mb-3">
                        <label>Total Amount</label>
                        <input type="text" class="form-control" id="final-amount" readonly>
                    </div>
                    <div class="mb-3">
                        <label>Payment Method</label>
                        <select class="form-control" id="payment-method">
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                        </select>
                    </div>
                    <div class="mb-3" id="cash-payment-section">
                        <label>Paid Amount</label>
                        <input type="number" class="form-control" id="paid-amount">
                        <div class="mt-2">
                            <label>Change: $<span id="change-amount">0.00</span></label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="complete-sale-btn">Complete Sale</button>
            </div>
        </div>
    </div>
</div>
@endsection
