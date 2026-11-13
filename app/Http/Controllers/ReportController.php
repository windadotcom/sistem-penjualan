<?php

namespace App\Http\Controllers;

use App\Exports\SalesExport;
use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'from' => ['nullable', 'date'],
            'to'   => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $sales = $this->query($request)
            ->with(['items', 'user'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $total   = $this->query($request)->sum('total');
        $count   = $this->query($request)->count();
        $average = $count ? round($total / $count) : 0;

        return view('reports.index', compact('sales', 'total', 'count', 'average'));
    }

    public function csv(Request $request)
    {
        $sales = $this->query($request)->with(['items', 'user'])->latest()->get();

        return response()->streamDownload(function () use ($sales) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // supaya Excel membaca karakter dengan benar

            fputcsv($out, ['Waktu', 'Kasir', 'Barang', 'Total', 'Dibayar'], ';', '"', '\\');

            foreach ($sales as $s) {
                fputcsv($out, [
                    $s->created_at->format('d/m/Y H:i'),
                    $s->cashier_name,
                    $s->items->map(fn ($i) => $i->product_name . ' x' . $i->qty)->join(', '),
                    $s->total,
                    $s->paid,
                ], ';', '"', '\\');
            }

            fclose($out);
        }, 'laporan-penjualan-' . now()->format('Ymd') . '.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function pdf(Request $request)
    {
        $request->validate([
            'from' => ['nullable', 'date'],
            'to'   => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $sales = $this->query($request)->with(['items', 'user'])->latest()->get();

        return Pdf::loadView('reports.pdf', [
                'sales'   => $sales,
                'total'   => $sales->sum('total'),
                'count'   => $sales->count(),
                'average' => $sales->count() ? round($sales->sum('total') / $sales->count()) : 0,
                'from'    => $request->from,
                'to'      => $request->to,
            ])
            ->setPaper('a4', 'portrait')
            ->download('laporan-penjualan-' . now()->format('Ymd') . '.pdf');
    }

    public function excel(Request $request)
    {
        $request->validate([
            'from' => ['nullable', 'date'],
            'to'   => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $sales = $this->query($request)->with(['items', 'user'])->latest()->get();

        return Excel::download(
            new SalesExport($sales),
            'laporan-penjualan-' . now()->format('Ymd') . '.xlsx'
        );
    }

    private function query(Request $request)
    {
        return Sale::query()
            ->valid()
            ->when($request->filled('from'), fn ($q) => $q->whereDate('created_at', '>=', $request->from))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('created_at', '<=', $request->to));
    }
}