<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $sales = $this->query($request)
            ->with(['items', 'user'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $total = $this->query($request)->sum('total');

        return view('reports.index', compact('sales', 'total'));
    }

    public function csv(Request $request)
    {
        $sales = $this->query($request)->with(['items', 'user'])->latest()->get();

        return response()->streamDownload(function () use ($sales) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Waktu', 'Kasir', 'Barang', 'Total', 'Dibayar'], ',', '"', '\\');

            foreach ($sales as $s) {
                fputcsv($out, [
                    $s->created_at->format('Y-m-d H:i'),
                    $s->user->name,
                    $s->items->map(fn ($i) => $i->product_name . ' x' . $i->qty)->join('; '),
                    $s->total,
                    $s->paid,
                ], ',', '"', '\\');
            }

            fclose($out);
        }, 'laporan-penjualan.csv', ['Content-Type' => 'text/csv']);
    }

    private function query(Request $request)
    {
        return Sale::query()
           ->valid()
           ->when($request->from, fn ($q, $d) => $q->whereDate('created_at', '>=', $d))
           ->when($request->to, fn ($q, $d) => $q->whereDate('created_at', '<=', $d));
    }
}