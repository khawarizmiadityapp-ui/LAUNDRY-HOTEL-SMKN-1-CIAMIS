@php
    $itemsText = "";
    foreach ($transaksi->details as $detail) {
        $namaLayanan = $detail->layanan->nama ?? 'Layanan';
        $qty = rtrim(rtrim(number_format($detail->qty, 2, ',', '.'), '0'), ',');
        $price = number_format($detail->price, 0, ',', '.');
        $subtotal = number_format($detail->subtotal, 0, ',', '.');
        $itemsText .= "• {$namaLayanan} ({$qty}x) = Rp {$subtotal}\n";
    }
    if (empty($itemsText)) {
        // Fallback
        $pricePerKg = $transaksi->price_per_kg > 0 ? $transaksi->price_per_kg : ($transaksi->service_type === 'express' ? 12000 : 6000);
        $itemsText = "• " . ucfirst($transaksi->service_type) . " ({$transaksi->weight} kg) @ Rp " . number_format($pricePerKg, 0, ',', '.') . " = Rp " . number_format($transaksi->total_price, 0, ',', '.') . "\n";
    }

    $waMessage = "Halo *" . ($transaksi->customer_name ?: 'Pelanggan') . "*,\nTerima kasih telah menggunakan jasa *Bening Laundry*.\n\nBerikut rincian pesanan Anda:\n📌 No. Invoice: *#" . $transaksi->transaksi_code . "*\n📅 Tanggal: " . $transaksi->created_at->format('d/m/Y H:i') . "\n\n*Rincian Layanan:*\n" . $itemsText . "\n💰 *Total Tagihan: Rp " . number_format($transaksi->total_price, 0, ',', '.') . "*\n💳 Pembayaran: " . strtoupper($transaksi->payment_method) . " (" . ($transaksi->payment_status === 'lunas' ? 'Lunas' : 'Belum Lunas') . ")\n\nLacak status laundry Anda secara real-time di sini:\n" . route('track.status', ['nota_number' => $transaksi->transaksi_code]);
    $waPhone = preg_replace('/[^0-9]/', '', $transaksi->customer_phone);
    if (str_starts_with($waPhone, '0')) {
        $waPhone = '62' . substr($waPhone, 1);
    }
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota #{{ $transaksi->transaksi_code }} — Bening Laundry</title>

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

        .wet-stamp {
            margin: 1rem auto 0;
            width: 110px;
            height: 110px;
            border: 2px dashed rgba(30, 64, 175, 0.4);
            border-radius: 999px;
            color: rgba(30, 64, 175, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 0.6rem;
            font-weight: 800;
            line-height: 1.3;
            letter-spacing: 0.05em;
            transform: rotate(-10deg);
        }
    </style>
</head>
<body>

    {{-- Action Buttons --}}
    <div class="actions no-print" style="max-width:340px; width:100%; margin-bottom:1rem;">
        <button onclick="window.print()" class="btn btn-primary">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18.75 3H5.25"/></svg>
            Cetak
        </button>
        <a href="https://wa.me/{{ $waPhone }}?text={{ urlencode($waMessage) }}" target="_blank" class="btn" style="background:#25D366; color:#fff; box-shadow: 0 4px 14px rgba(37,211,102,0.35);">
            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            Kirim WA
        </a>
        <a href="{{ route('admin.pos.index') }}" class="btn btn-secondary">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Pesanan Baru
        </a>
    </div>

    {{-- Success banner --}}
    @if(session('success'))
    <div class="no-print" style="max-width:340px; width:100%; margin-bottom:1rem; background:#dcfce7; color:#16a34a; padding:12px 16px; border-radius:12px; font-size:0.85rem; font-weight:500; display:flex; align-items:center; gap:8px;">
        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Nota Card --}}
    <div class="nota-card">

        {{-- Header --}}
        <div class="nota-header">
            <div class="logo-text">Bening Laundry</div>
            <div class="logo-sub">SMKN 1 Ciamis • Hotel Laundry</div>
            <div class="logo-sub">Jl. Jend. Sudirman No. 99, Ciamis</div>
            <div class="nota-code">No: #{{ $transaksi->transaksi_code }}</div>
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
                    <span>{{ $transaksi->user->name ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <label>Status</label>
                    <span>{{ ucfirst($transaksi->status) }}</span>
                </div>
                <div class="info-row">
                    <label>Bayar</label>
                    <span>{{ $transaksi->payment_status === 'lunas' ? 'LUNAS' : 'BELUM LUNAS' }}</span>
                </div>
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
                <div class="totals-row">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>

                <div class="totals-row grand">
                    <span>TOTAL</span>
                    <span>Rp {{ number_format($totalTagihan, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Payment Info --}}
            <div class="payment-info">
                {{ strtoupper($transaksi->payment_method) }}
            </div>

            <div class="wet-stamp">
                BENING LAUNDRY<br>
                CAP BASAH<br>
                {{ now()->format('d/m/Y') }}
            </div>

            @if($transaksi->notes)
            <div class="divider"></div>
            <div style="font-size:0.7rem;">
                <span style="font-weight:700;">Catatan:</span>
                {{ $transaksi->notes }}
            </div>
            @endif
        </div>

        <div class="divider"></div>

        {{-- Footer --}}
        <div class="nota-footer">
            <p class="thanks">Terima kasih!</p>
            <p>Simpan struk ini sebagai<br>bukti transaksi Anda.</p>
        </div>
    </div>

    {{-- Pop-up Modal WA --}}
    @if(session('success'))
    <div id="waModal" class="no-print" style="position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); display: flex; align-items: center; justify-content: center; z-index: 99999; padding: 1rem;">
        <div style="background: #fff; border-radius: 20px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); max-width: 400px; width: 100%; overflow: hidden; animation: modalFadeIn 0.3s ease-out;">
            <div style="background: linear-gradient(135deg, #25D366 0%, #128C7E 100%); color: #fff; padding: 2rem 1.5rem; text-align: center; position: relative;">
                <div style="background: rgba(255, 255, 255, 0.2); width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                    <svg width="36" height="36" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                </div>
                <h3 style="font-family: 'Syne', sans-serif; font-size: 1.25rem; font-weight: 700; margin-bottom: 0.25rem; text-align: center;">Kirim Nota via WA?</h3>
                <p style="font-size: 0.85rem; opacity: 0.9; text-align: center; line-height: 1.4;">Kirim rincian transaksi langsung ke nomor WhatsApp pelanggan agar tidak lupa.</p>
            </div>
            <div style="padding: 1.5rem; display: flex; flex-direction: column; gap: 10px;">
                <a id="btnSendWA" href="https://wa.me/{{ $waPhone }}?text={{ urlencode($waMessage) }}" target="_blank" onclick="closeWaModal()" style="display: flex; align-items: center; justify-content: center; gap: 8px; background: #25D366; color: #fff; text-decoration: none; padding: 12px; border-radius: 12px; font-weight: 600; font-size: 0.9rem; box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3); transition: all 0.2s;">
                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Kirim ke WhatsApp
                </a>
                <button onclick="window.print(); closeWaModal();" style="display: flex; align-items: center; justify-content: center; gap: 8px; background: #3568f4; color: #fff; border: none; padding: 12px; border-radius: 12px; font-weight: 600; font-size: 0.9rem; cursor: pointer; transition: all 0.2s;">
                    Cetak Nota
                </button>
                <button onclick="closeWaModal()" style="display: flex; align-items: center; justify-content: center; background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; padding: 10px; border-radius: 12px; font-weight: 500; font-size: 0.85rem; cursor: pointer; transition: all 0.2s;">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <style>
        @keyframes modalFadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>

    <script>
        function closeWaModal() {
            document.getElementById('waModal').style.display = 'none';
        }
    </script>
    @endif

</body>
</html>
