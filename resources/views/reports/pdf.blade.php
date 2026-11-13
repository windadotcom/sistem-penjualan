<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #222; }
        h2 { margin: 0 0 4px 0; }
        .muted { color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #999; padding: 6px; vertical-align: top; }
        th { background: #eee; text-align: left; }
        .right { text-align: right; }
        .summary td { border: none; padding: 2px 0; }
    </style>
</head>
<body>
    <h2>Laporan Penjualan</h2>
    <div class="muted">
        Periode: {{ $from ? \Carbon\Carbon::parse($from)->format('d/m/Y') : 'awal' }}
        s/d {{ $to ? \Carbon\Carbon::parse($to)->format('d/m/Y') : 'sekarang' }}
        &middot; Dicetak: {{ now()->format('d/m/Y H:i') }}
    </div>

    <table class="summary" style="margin-top: 10px;">
        <tr><td width="140">Jumlah transaksi</td><td>: {{ $count }}</td></tr>
        <tr><td>Total penjualan</td><td>: Rp {{ number_format($total, 0, ',', '.') }}</td></tr>
        <tr><td>Rata-rata per transaksi</td><td>: Rp {{ number_format($average, 0, ',', '.') }}</td></tr>
    </table>

    <table>
        <thead>
            <tr>
                <th width="90">Waktu</th>
                <th width="90">Kasir</th>
                <th>Barang</th>
                <th class="right" width="80">Total</th>
                <th class="right" width="80">Dibayar</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sales as $s)
                <tr>
                    <td>{{ $s->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $s->cashier_name }}</td>
                    <td>{{ $s->items->map(fn ($i) => $i->product_name . ' x' . $i->qty)->join(', ') }}</td>
                    <td class="right">Rp {{ number_format($s->total, 0, ',', '.') }}</td>
                    <td class="right">{{ $s->source === 'kasir' ? 'Rp ' . number_format($s->paid, 0, ',', '.') : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>