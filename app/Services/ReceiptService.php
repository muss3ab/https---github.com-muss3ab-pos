<?php

namespace App\Services;

use App\Models\Sale;
use PDF;

class ReceiptService
{
    public function generateReceipt(Sale $sale)
    {
        $data = [
            'sale' => $sale->load('saleItems.product', 'user', 'store'),
            'company' => [
                'name' => setting('company_name'),
                'address' => setting('company_address'),
                'phone' => setting('company_phone'),
                'tax_rate' => setting('tax_rate')
            ]
        ];

        $pdf = PDF::loadView('receipts.template', $data);
        return $pdf;
    }

    public function generateThermalReceipt(Sale $sale)
    {
        $receipt = view('receipts.thermal', [
            'sale' => $sale->load('saleItems.product', 'user', 'store'),
            'company' => [
                'name' => setting('company_name'),
                'address' => setting('company_address'),
                'phone' => setting('company_phone')
            ]
        ])->render();

        return $receipt;
    }
}
