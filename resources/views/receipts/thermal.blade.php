<div class="receipt">
    <div class="text-center">
        <h3>{{ $receipt['header']['company_name'] }}</h3>
        <p>{{ $receipt['header']['address'] }}</p>
        <p>Tel: {{ $receipt['header']['phone'] }}</p>

        <div class="receipt-details">
            <p>Invoice: {{ $receipt['header']['invoice_number'] }}</p>
            <p>Date: {{ $receipt['header']['date'] }}</p>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($receipt['items'] as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>${{ number_format($item['price'], 2) }}</td>
                    <td>${{ number_format($item['subtotal'], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <p>Total: ${{ number_format($receipt['totals']['total'], 2) }}</p>
            <p>Paid: ${{ number_format($receipt['totals']['paid'], 2) }}</p>
            <p>Change: ${{ number_format($receipt['totals']['change'], 2) }}</p>
        </div>

        <p class="footer">{{ $receipt['footer'] }}</p>
    </div>
</div>
