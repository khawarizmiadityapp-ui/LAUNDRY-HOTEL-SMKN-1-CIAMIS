<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota #{{ $transaksi->transaksi_code }} — Bening Laundry</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=Syne:wght@600;700;800&display=swap" rel="stylesheet">

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
            }
            .no-print {
                display: none !important;
            }
            .nota-card {
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }
        }

        .nota-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 24px rgba(21,34,120,0.08);
            overflow: hidden;
            width: 100%;
            max-width: 480px;
        }

        /* Header gradient */
        .nota-header {
            background: linear-gradient(135deg, #3568f4 0%, #1736d6 100%);
            color: #fff;
            padding: 2rem 1.5rem 1.5rem;
            text-align: center;
            position: relative;
        }
        .nota-header::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 20px;
            background: #fff;
            border-radius: 20px 20px 0 0;
        }

        .logo-text {
            font-family: 'Syne', sans-serif;
            font-size: 1.35rem;
            font-weight: 800;
            letter-spacing: -0.02em;
        }
        .logo-sub {
            font-size: 0.7rem;
            opacity: 0.7;
            margin-top: 2px;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .nota-code {
            display: inline-block;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 10px;
            padding: 6px 16px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-top: 12px;
            letter-spacing: 0.04em;
        }

        /* Body */
        .nota-body {
            padding: 1.5rem;
        }

        /* Info rows */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 1.5rem;
            padding-bottom: 1.25rem;
            border-bottom: 1px dashed #e2e8f0;
        }
        .info-item label {
            display: block;
            font-size: 0.68rem;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 500;
            margin-bottom: 2px;
        }
        .info-item span {
            font-size: 0.85rem;
            font-weight: 600;
            color: #334155;
        }

        /* Table */
        .item-table {
            width: 100%;
            font-size: 0.82rem;
            border-collapse: collapse;
            margin-bottom: 1.25rem;
        }
        .item-table thead th {
            text-align: left;
            font-size: 0.68rem;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-weight: 600;
            padding: 6px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .item-table thead th:last-child,
        .item-table tbody td:last-child {
            text-align: right;
        }
        .item-table tbody td {
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
            color: #475569;
        }
        .item-table tbody td:first-child {
            font-weight: 500;
            color: #334155;
        }
        .item-table .qty-col {
            text-align: center;
            color: #64748b;
        }

        /* Totals */
        .totals {
            border-top: 1px dashed #e2e8f0;
            padding-top: 1rem;
            margin-bottom: 1rem;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.82rem;
            color: #64748b;
            padding: 4px 0;
        }
        .totals-row.grand {
            font-size: 1.05rem;
            font-weight: 700;
            color: #1e293b;
            padding-top: 8px;
            margin-top: 4px;
            border-top: 2px solid #1e293b;
        }
        .totals-row.grand .amount {
            color: #3568f4;
        }

        /* Status badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 0.72rem;
            font-weight: 600;
        }
        .badge-success {
            background: #dcfce7;
            color: #16a34a;
        }
        .badge-warning {
            background: #fef3c7;
            color: #d97706;
        }
        .badge-info {
            background: #dbeafe;
            color: #2563eb;
        }
        .badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        /* Footer */
        .nota-footer {
            text-align: center;
            padding: 1.25rem 1.5rem 1.5rem;
            border-top: 1px dashed #e2e8f0;
        }
        .nota-footer p {
            font-size: 0.75rem;
            color: #94a3b8;
            line-height: 1.6;
        }
        .nota-footer .thanks {
            font-size: 0.9rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 4px;
        }

        /* Action buttons */
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
            display: flex;
            gap: 8px;
            align-items: center;
            justify-content: center;
            margin-top: 8px;
        }
    </style>
</head>
<body>

    {{-- Action Buttons --}}
    <div class="actions no-print" style="max-width:480px; width:100%; margin-bottom:1rem;">
        <button onclick="window.print()" class="btn btn-primary">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18.75 3H5.25"/></svg>
            Cetak Nota
        </button>
        <a href="{{ route('admin.pos.index') }}" class="btn btn-secondary">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Pesanan Baru
        </a>
    </div>

    {{-- Success banner --}}
    @if(session('success'))
    <div class="no-print" style="max-width:480px; width:100%; margin-bottom:1rem; background:#dcfce7; color:#16a34a; padding:12px 16px; border-radius:12px; font-size:0.85rem; font-weight:500; display:flex; align-items:center; gap:8px;">
        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Nota Card --}}
    <div class="nota-card">

        {{-- Header --}}
        <div class="nota-header">
            <div class="logo-text">Bening Laundry</div>
            <div class="logo-sub">SMKN 1 Ciamis • Hotel Laundry Service</div>
            <div class="nota-code">#{{ $transaksi->transaksi_code }}</div>
        </div>

        {{-- Body --}}
        <div class="nota-body">

            {{-- Info --}}
            <div class="info-grid">
                <div class="info-item">
                    <label>Customer</label>
                    <span>{{ $transaksi->customer_name }}</span>
                </div>
                <div class="info-item">
                    <label>No. HP</label>
                    <span>{{ $transaksi->customer_phone }}</span>
                </div>
                <div class="info-item">
                    <label>Tanggal</label>
                    <span>{{ $transaksi->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="info-item">
                    <label>Petugas</label>
                    <span>{{ $transaksi->user->name ?? '-' }}</span>
                </div>
            </div>

            {{-- Status Row --}}
            <div style="display:flex; gap:8px; margin-bottom:1.25rem; flex-wrap:wrap;">
                <span class="badge badge-info">
                    <span class="badge-dot"></span>
                    {{ ucfirst($transaksi->status) }}
                </span>
                @if($transaksi->payment_status === 'lunas')
                    <span class="badge badge-success">
                        <span class="badge-dot"></span>
                        Lunas
                    </span>
                @else
                    <span class="badge badge-warning">
                        <span class="badge-dot"></span>
                        Deposit
                    </span>
                @endif
            </div>

            {{-- Items Table --}}
            <table class="item-table">
                <thead>
                    <tr>
                        <th>Layanan</th>
                        <th style="text-align:center">Qty</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksi->details as $detail)
                    <tr>
                        <td>{{ $detail->layanan->nama ?? 'Layanan' }}</td>
                        <td class="qty-col">{{ rtrim(rtrim(number_format($detail->qty, 2, ',', '.'), '0'), ',') }}</td>
                        <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center; color:#94a3b8; padding:16px;">
                            {{ $transaksi->service_type }} — {{ $transaksi->weight }}kg × Rp {{ number_format($transaksi->price_per_kg) }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Totals --}}
            <div class="totals">
                <div class="totals-row">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>

                <div class="totals-row grand">
                    <span>Total Tagihan</span>
                    <span class="amount">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Payment Info --}}
            <div class="payment-info">
                <span class="badge badge-info" style="text-transform:uppercase;">
                    {{ $transaksi->payment_method }}
                </span>
            </div>

            @if($transaksi->notes)
            <div style="margin-top:1rem; padding:10px 14px; background:#f8fafc; border-radius:10px; border:1px solid #f1f5f9;">
                <p style="font-size:0.7rem; color:#94a3b8; text-transform:uppercase; letter-spacing:0.06em; font-weight:500; margin-bottom:2px;">Catatan</p>
                <p style="font-size:0.82rem; color:#475569;">{{ $transaksi->notes }}</p>
            </div>
            @endif
        </div>

        {{-- Footer --}}
        <div class="nota-footer">
            <p class="thanks">Terima kasih atas kepercayaan Anda! 🙏</p>
            <p>Bening Laundry — SMKN 1 Ciamis<br>Jl. Jend. Sudirman No. 99, Ciamis</p>
        </div>
    </div>

</body>
</html>
