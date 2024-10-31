<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return Sale::whereBetween('created_at', [$this->startDate, $this->endDate])
            ->with(['saleItems.product'])
            ->get();
    }

    public function headings(): array
    {
        return [
            'Invoice Number',
            'Date',
            'Items',
            'Total Amount',
            'Payment Method',
            'Customer'
        ];
    }

    public function map($sale): array
    {
        return [
            $sale->invoice_number,
            $sale->created_at->format('Y-m-d H:i'),
            $sale->saleItems->map(function($item) {
                return $item->product->name . ' x ' . $item->quantity;
            })->implode(', '),
            $sale->total_amount,
            $sale->payment_method,
            $sale->customer ? $sale->customer->name : 'Walk-in'
        ];
    }
} 
