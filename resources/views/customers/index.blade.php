@extends('layouts.pos')

@section('content')
<div class="container">
    <div class="mb-4 row">
        <div class="col-md-6">
            <h2>Customers</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('customers.create') }}" class="btn btn-primary">
                Add New Customer
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Total Spent</th>
                            <th>Last Visit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                        <tr>
                            <td>{{ $customer->name }}</td>
                            <td>
                                {{ $customer->email }}<br>
                                {{ $customer->phone }}
                            </td>
                            <td>${{ number_format($customer->total_spent, 2) }}</td>
                            <td>{{ $customer->last_visit?->format('Y-m-d') ?? 'Never' }}</td>
                            <td>
                                <a href="{{ route('customers.show', $customer) }}"
                                   class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('customers.edit', $customer) }}"
                                   class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ route('customers.destroy', $customer) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $customers->links() }}
        </div>
    </div>
</div>
@endsection
