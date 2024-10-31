@extends('layouts.pos')

@section('content')
<div class="container">
    <!-- Metrics Cards -->
    <div class="mb-4 row">
        <div class="col-md-3">
            <div class="text-white card bg-primary">
                <div class="card-body">
                    <h5>Today's Sales</h5>
                    <h3>${{ number_format($metrics['today_sales'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="text-white card bg-success">
                <div class="card-body">
                    <h5>Transactions Today</h5>
                    <h3>{{ $metrics['today_transactions'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="text-white card bg-warning">
                <div class="card-body">
                    <h5>Low Stock Items</h5>
                    <h3>{{ $metrics['low_stock_items'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="text-white card bg-info">
                <div class="card-body">
                    <h5>Total Customers</h5>
                    <h3>{{ $metrics['total_customers'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="mb-4 row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Sales Last 7 Days
                </div>
                <div class="card-body">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Top Selling Products
                </div>
                <div class="card-body">
                    <canvas id="productsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Alerts -->
    <div class="card">
        <div class="card-header">
            Low Stock Alerts
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Current Stock</th>
                        <th>Alert Level</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lowStockProducts as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>{{ $product->alert_stock }}</td>
                        <td>
                            <button class="btn btn-sm btn-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#restockModal"
                                    data-product-id="{{ $product->id }}">
                                Restock
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Sales Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($dailySales->pluck('date')) !!},
            datasets: [{
                label: 'Daily Sales',
                data: {!! json_encode($dailySales->pluck('total')) !!},
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        }
    });

    // Products Chart
    const productsCtx = document.getElementById('productsChart').getContext('2d');
    new Chart(productsCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($topProducts->pluck('name')) !!},
            datasets: [{
                data: {!! json_encode($topProducts->pluck('total_sold')) !!},
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF'
                ]
            }]
        }
    });
</script>
@endpush
@endsection
