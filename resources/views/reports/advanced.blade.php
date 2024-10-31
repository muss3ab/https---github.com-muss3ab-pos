@extends('layouts.pos')

@section('content')
<div class="container">
    <div class="mb-4 row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Advanced Sales Report</h4>
                </div>
                <div class="card-body">
                    <form id="report-form" class="row">
                        <div class="col-md-4">
                            <input type="date" name="start_date" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <input type="date" name="end_date" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">Generate Report</button>
                            <button type="button" class="btn btn-secondary" id="export-excel">Export Excel</button>
                            <button type="button" class="btn btn-secondary" id="export-pdf">Export PDF</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Sales Metrics -->
        <div class="mb-4 col-md-6">
            <div class="card">
                <div class="card-header">Sales Overview</div>
                <div class="card-body">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Category Distribution -->
        <div class="mb-4 col-md-6">
            <div class="card">
                <div class="card-header">Sales by Category</div>
                <div class="card-body">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional charts and tables -->
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Add chart initialization and data handling
</script>
@endpush
@endsection
