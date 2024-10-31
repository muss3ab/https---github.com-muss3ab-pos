@extends('layouts.pos')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Sales Report</h5>
                    <form action="{{ route('reports.sales') }}" method="GET">
                        <div class="mb-3">
                            <label>Start Date</label>
                            <input type="date" name="start_date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>End Date</label>
                            <input type="date" name="end_date" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Generate Report</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Inventory Report</h5>
                    <a href="{{ route('reports.inventory') }}" class="btn btn-primary">
                        Generate Inventory Report
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
