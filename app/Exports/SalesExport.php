<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(private Collection $sales) {}

    public function collection()
    {
        return $this->sales;
    }

    public function headings(): array
    {
        return ['Waktu', 'Kasir', 'Barang', 'Total', 'Dibayar'];
    }

    public function map($sale): array
    {
        return [
            $sale->created_at->format('d/m/Y H:i'),
            $sale->cashier_name,
            $sale->items->map(fn ($i) => $i->product_name . ' x' . $i->qty)->join(', '),
            $sale->total,
            $sale->paid,
        ];
    }
}