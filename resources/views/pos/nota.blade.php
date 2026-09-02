@php
    $isLunas = ($transaksi->payment_status === 'lunas');

    $itemsText = "";
    foreach ($transaksi->details as $detail) {
        $namaLayanan = $detail->layanan->nama ?? 'Layanan';
        $qty = rtrim(rtrim(number_format($detail->qty, 2, ',', '.'), '0'), ',');
        $price = number_format($detail->price, 0, ',', '.');
        $detailSubtotal = number_format($detail->subtotal, 0, ',', '.');
        $itemsText .= "• {$namaLayanan} ({$qty}x) = Rp {$detailSubtotal}\n";
    }
    if (empty($itemsText)) {
        // Fallback
        $pricePerKg = $transaksi->price_per_kg > 0 ? $transaksi->price_per_kg : ($transaksi->service_type === 'express' ? 12000 : 6000);
        $itemsText = "• " . ucfirst($transaksi->service_type) . " ({$transaksi->weight} kg) @ Rp " . number_format($pricePerKg, 0, ',', '.') . " = Rp " . number_format($transaksi->total_price, 0, ',', '.') . "\n";
    }

    // Calculate estimated completion
    $maxEstimasiJam = 0;
    foreach ($transaksi->details as $detail) {
        if ($detail->layanan && $detail->layanan->estimasi) {
            $maxEstimasiJam = max($maxEstimasiJam, (int) $detail->layanan->estimasi);
        }
    }
    $estimasiSelesai = $maxEstimasiJam > 0
        ? $transaksi->created_at->copy()->addHours($maxEstimasiJam)
        : null;
    $estimasiText = $estimasiSelesai 
        ? "\n📅 *Estimasi Selesai: " . $estimasiSelesai->format('d/m/Y H:i') . " WIB*" 
        : "";

    $statusBayarText = $isLunas ? 'Lunas (Sudah Dibayar)' : 'INVOICE (Belum Lunas)';
    $instruksiBayar = $isLunas 
        ? "✅ *Status: LUNAS*\nTerima kasih atas pembayaran Anda." 
        : "⚠️ *Status: INVOICE (Belum Lunas)*\nMohon lakukan pelunasan saat pengambilan cucian.";

    $waMessage = "Halo *" . ($transaksi->customer_name ?: 'Pelanggan') . "*,\nTerima kasih telah menggunakan jasa *Bening Laundry*.\n\nBerikut rincian pesanan Anda:\n📌 No. " . ($isLunas ? 'Struk' : 'Invoice') . ": *#" . $transaksi->transaksi_code . "*\n📅 Tanggal: " . $transaksi->created_at->format('d/m/Y H:i') . $estimasiText . "\n\n*Rincian Layanan:*\n" . $itemsText . "\n💰 *Total " . ($isLunas ? 'Tagihan' : 'Tagihan (Invoice)') . ": Rp " . number_format($transaksi->total_price, 0, ',', '.') . "*\n💳 Pembayaran: " . strtoupper($transaksi->payment_method) . " (" . $statusBayarText . ")\n" . $instruksiBayar . "\n\nLacak status laundry Anda secara real-time di sini:\n" . route('track.status', ['nota_number' => $transaksi->transaksi_code]);
    $waPhone = format_whatsapp_number($transaksi->customer_phone);
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isLunas ? 'Nota Struk #' . $transaksi->transaksi_code : 'Invoice #' . $transaksi->transaksi_code }} — Bening Laundry</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=JetBrains+Mono:wght@400;500;700;800&family=Syne:wght@600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #f1f5f9;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem 1rem;
        }

        /* Print styles */
        @media print {
            body {
                background: #fff;
                padding: 0;
                color: #000;
            }
            .no-print {
                display: none !important;
            }
            .nota-card {
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                max-width: 80mm !important; /* Standard thermal receipt size */
            }
        }

        /* Thermal receipt styling */
        .nota-card {
            background: #fff;
            font-family: 'JetBrains Mono', 'Consolas', 'Courier New', monospace;
            box-shadow: 0 4px 24px rgba(15, 23, 42, 0.05);
            border: 1px dashed #cbd5e1;
            width: 100%;
            max-width: 340px;
            padding: 24px 16px;
            color: #000;
        }

        .nota-header {
            text-align: center;
            margin-bottom: 12px;
        }

        .logo-text {
            font-size: 1.15rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .logo-sub {
            font-size: 0.7rem;
            margin-top: 2px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .nota-code {
            display: block;
            font-size: 0.8rem;
            font-weight: 700;
            margin-top: 6px;
        }

        /* Body */
        .nota-body {
            padding: 0;
        }

        /* Info rows */
        .info-grid {
            margin-bottom: 10px;
            font-size: 0.75rem;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }
        .info-row label {
            color: #444;
        }
        .info-row span {
            font-weight: bold;
        }

        /* Dotted/dashed separators */
        .divider {
            border-top: 1px dashed #000;
            margin: 10px 0;
            height: 0;
        }

        /* Stacked columns for thermal receipt look */
        .item-list {
            margin-bottom: 10px;
            font-size: 0.78rem;
        }
        .item-row {
            margin-bottom: 8px;
        }
        .item-name {
            font-weight: 700;
        }
        .item-calc-row {
            display: flex;
            justify-content: space-between;
            padding-left: 10px;
            color: #333;
        }

        /* Totals */
        .totals {
            margin-bottom: 10px;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.8rem;
            padding: 2px 0;
        }
        .totals-row.grand {
            font-size: 0.9rem;
            font-weight: 800;
            border-top: 1px dashed #000;
            padding-top: 6px;
            margin-top: 4px;
        }

        /* Footer */
        .nota-footer {
            text-align: center;
            font-size: 0.7rem;
            color: #333;
        }
        .nota-footer .thanks {
            font-size: 0.75rem;
            font-weight: 700;
            margin-bottom: 4px;
        }

        /* Action buttons panel */
        .actions {
            display: flex;
            gap: 12px;
            margin-top: 1.5rem;
        }
        .btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 12px 20px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
            text-decoration: none;
            border: none;
        }
        .btn-primary {
            background: linear-gradient(135deg, #3568f4 0%, #1736d6 100%);
            color: #fff;
            box-shadow: 0 4px 14px rgba(53,104,244,0.35);
        }
        .btn-primary:hover {
            box-shadow: 0 6px 20px rgba(53,104,244,0.45);
            transform: translateY(-1px);
        }
        .btn-secondary {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }
        .btn-secondary:hover {
            background: #e2e8f0;
        }

        .payment-info {
            text-align: center;
            font-weight: 800;
            font-size: 0.85rem;
            margin-top: 6px;
            text-transform: uppercase;
        }

        /* Watermark style */
        .nota-card {
            position: relative;
            overflow: hidden; /* clip the watermark */
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 2.2rem;
            font-weight: 900;
            color: rgba(0, 0, 0, 0.045);
            white-space: nowrap;
            pointer-events: none;
            text-transform: uppercase;
            letter-spacing: 2px;
            z-index: 0;
            user-select: none;
        }
    </style>
</head>
<body>

    {{-- Hidden WA Link for Parent Modal --}}
    <a id="waLink" href="https://wa.me/{{ $waPhone }}?text={{ urlencode($waMessage) }}" style="display:none;"></a>

    {{-- Success banner --}}
    @if(session('success'))
    <div class="no-print" style="max-width:340px; width:100%; margin-bottom:1rem; background:#dcfce7; color:#16a34a; padding:12px 16px; border-radius:12px; font-size:0.85rem; font-weight:500; display:flex; align-items:center; gap:8px;">
        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Nota / Invoice Card --}}
    <div class="nota-card">
        <div class="watermark">{{ $isLunas ? 'BENING LAUNDRY' : 'INVOICE' }}</div>

        {{-- Header --}}
        <div class="nota-header">
            <div class="logo-text">Bening Laundry</div>
            <div class="logo-sub">SMKN 1 Ciamis • Hotel Laundry</div>
            <div class="logo-sub">Jl. Jend. Sudirman No. 99, Ciamis</div>
            
            <div style="margin: 8px 0 4px; display: inline-block; padding: 2px 10px; border-radius: 4px; font-weight: 800; font-size: 0.78rem; letter-spacing: 1px; {{ $isLunas ? 'background: #000; color: #fff;' : 'background: #fff; color: #000; border: 1.5px dashed #000;' }}">
                {{ $isLunas ? 'STRUK PEMBAYARAN' : 'INVOICE / TAGIHAN' }}
            </div>

            <div class="nota-code">{{ $isLunas ? 'No. Struk' : 'No. Invoice' }}: #{{ $transaksi->transaksi_code }}</div>
        </div>

        <div class="divider"></div>

        {{-- Body --}}
        <div class="nota-body">

            {{-- Info --}}
            <div class="info-grid">
                <div class="info-row">
                    <label>Pelanggan</label>
                    <span>{{ $transaksi->customer_name }}</span>
                </div>
                <div class="info-row">
                    <label>No. HP</label>
                    <span>{{ $transaksi->customer_phone }}</span>
                </div>
                <div class="info-row">
                    <label>Tanggal</label>
                    <span>{{ $transaksi->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="info-row">
                    <label>Kasir</label>
                    <span>{{ $transaksi->kasir_name ?? $transaksi->user->name ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <label>Status Cucian</label>
                    <span>{{ ucfirst($transaksi->status) }}</span>
                </div>
                <div class="info-row">
                    <label>Status Bayar</label>
                    <span>{{ $isLunas ? 'LUNAS' : 'INVOICE (BELUM LUNAS)' }}</span>
                </div>
                @if($estimasiSelesai)
                <div class="info-row" style="margin-top: 4px; padding-top: 4px; border-top: 1px dotted #ddd;">
                    <label>Est. Selesai</label>
                    <span>{{ $estimasiSelesai->format('d/m/Y H:i') }}</span>
                </div>
                @endif
            </div>

            <div class="divider"></div>

            {{-- Items --}}
            <div class="item-list">
                @forelse($transaksi->details as $detail)
                <div class="item-row">
                    <div class="item-name">{{ $detail->layanan->nama ?? 'Layanan' }}</div>
                    <div class="item-calc-row">
                        <span>{{ rtrim(rtrim(number_format($detail->qty, 2, ',', '.'), '0'), ',') }} x Rp {{ number_format($detail->price, 0, ',', '.') }}</span>
                        <span>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                    </div>
                </div>
                @empty
                <div class="item-row">
                    <div class="item-name">{{ ucfirst($transaksi->service_type) }}</div>
                    <div class="item-calc-row">
                        <span>{{ $transaksi->weight }}kg x Rp {{ number_format($transaksi->price_per_kg, 0, ',', '.') }}</span>
                        <span>Rp {{ number_format($transaksi->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>
                @endforelse
            </div>

            <div class="divider"></div>

            {{-- Totals --}}
            <div class="totals">
                @if($transaksi->discount > 0)
                <div class="totals-row">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="totals-row">
                    <span>Diskon</span>
                    <span>-Rp {{ number_format($transaksi->discount, 0, ',', '.') }}</span>
                </div>
                @endif

                <div class="totals-row grand">
                    <span>{{ $isLunas ? 'TOTAL' : 'TOTAL TAGIHAN' }}</span>
                    <span>Rp {{ number_format($totalTagihan, 0, ',', '.') }}</span>
                </div>

                @if($isLunas && $transaksi->payment_method === 'tunai')
                <div class="totals-row" style="margin-top: 6px; font-size: 0.75rem;">
                    <span>Uang Pelanggan</span>
                    <span>Rp {{ number_format($transaksi->dibayar, 0, ',', '.') }}</span>
                </div>
                <div class="totals-row" style="font-size: 0.75rem;">
                    <span>Kembalian</span>
                    <span>Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</span>
                </div>
                @endif
            </div>

            @if(!$isLunas)
            <div style="text-align: center; margin: 8px 0 10px; padding: 6px 4px; border: 1px dashed #000; font-size: 0.72rem; font-weight: 700; line-height: 1.3;">
                [ ! ] STATUS: INVOICE (BELUM LUNAS)<br>
                <span style="font-size: 0.65rem; font-weight: 500;">Harap melunasi tagihan saat pengambilan</span>
            </div>
            @endif

            {{-- Payment Info --}}
            <div class="payment-info" style="margin-bottom: 12px;">
                METODE: {{ strtoupper($transaksi->payment_method) }} ({{ $isLunas ? 'LUNAS' : 'INVOICE' }})
            </div>

        </div>

        <div class="divider"></div>

        {{-- Footer --}}
        <div class="nota-footer">
            <p class="thanks">Terima kasih!</p>
            @if($isLunas)
                <p>Simpan struk ini sebagai<br>bukti pembayaran transaksi Anda.</p>
            @else
                <p>Simpan invoice ini sebagai<br>bukti rincian tagihan pesanan Anda.<br>Pelunasan dilakukan saat pengambilan.</p>
            @endif
        </div>
    </div>



</body>
</html>
