<?php

use App\Services\MenuService;

if (!function_exists('menu_service')) {
    /**
     * Get MenuService instance
     *
     * @return MenuService
     */
    function menu_service(): MenuService
    {
        return app(MenuService::class);
    }
}

if (!function_exists('get_user_menus')) {
    /**
     * Get menus for current user
     *
     * @param string $type 'admin' or 'petugas'
     * @return array
     */
    function get_user_menus(string $type = 'petugas'): array
    {
        return menu_service()->getMenusForUser($type);
    }
}

if (!function_exists('get_division_label')) {
    /**
     * Get division label for display
     *
     * @param string|null $division
     * @return string
     */
    function get_division_label(?string $division): string
    {
        return menu_service()->getDivisionLabel($division);
    }
}

if (!function_exists('get_user_initials')) {
    /**
     * Get user initials for avatar
     *
     * @param string|null $name
     * @return string
     */
    function get_user_initials(?string $name): string
    {
        return menu_service()->getUserInitials($name);
    }
}

if (!function_exists('format_rupiah')) {
    /**
     * Format number to Rupiah currency
     *
     * @param float|int $amount
     * @param bool $withPrefix
     * @return string
     */
    function format_rupiah($amount, bool $withPrefix = true): string
    {
        $formatted = number_format($amount, 0, ',', '.');
        return $withPrefix ? "Rp {$formatted}" : $formatted;
    }
}

if (!function_exists('rupiah')) {
    /**
     * Format number to Rupiah currency (alias for format_rupiah)
     *
     * @param float|int $amount
     * @param bool $withPrefix
     * @return string
     */
    function rupiah($amount, bool $withPrefix = true): string
    {
        return format_rupiah($amount, $withPrefix);
    }
}

if (!function_exists('status_badge_class')) {
    /**
     * Get CSS class for status badge
     *
     * @param string $status
     * @return string
     */
    function status_badge_class(string $status): string
    {
        return match (strtolower($status)) {
            'pending', 'menunggu' => 'bg-yellow-100 text-yellow-700',
            'in_progress', 'proses', 'processing' => 'bg-blue-100 text-blue-700',
            'completed', 'selesai', 'done' => 'bg-green-100 text-green-700',
            'cancelled', 'dibatalkan', 'canceled' => 'bg-red-100 text-red-700',
            'paid', 'lunas' => 'bg-emerald-100 text-emerald-700',
            'unpaid', 'belum_lunas' => 'bg-orange-100 text-orange-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }
}

if (!function_exists('status_label')) {
    /**
     * Get human-readable status label
     *
     * @param string $status
     * @return string
     */
    function status_label(string $status): string
    {
        return match (strtolower($status)) {
            'pending' => 'Menunggu',
            'in_progress', 'processing' => 'Dalam Proses',
            'completed', 'done' => 'Selesai',
            'cancelled', 'canceled' => 'Dibatalkan',
            'paid' => 'Lunas',
            'unpaid' => 'Belum Lunas',
            default => ucfirst(str_replace('_', ' ', $status)),
        };
    }
}

if (!function_exists('format_whatsapp_number')) {
    /**
     * Normalize and format a phone number to WhatsApp country-code standard format (e.g. 6282116035029)
     *
     * @param string|null $phone
     * @return string
     */
    function format_whatsapp_number(?string $phone): string
    {
        if (empty($phone)) {
            return '';
        }

        // 1. Remove all non-numeric characters (using \D is faster than [^0-9])
        $phone = preg_replace('/\D/', '', $phone);

        // 2. Fast-path prefix checks with early returns to save CPU cycles
        if (str_starts_with($phone, '620')) {
            return '62' . substr($phone, 3);
        }

        if (str_starts_with($phone, '0')) {
            return '62' . substr($phone, 1);
        }

        if (str_starts_with($phone, '8')) {
            return '62' . $phone;
        }

        return $phone;
    }
}

if (!function_exists('mask_phone_number')) {
    /**
     * Mask a phone number for public display, keeping the first 4 and last 3 digits visible.
     * Example: 082116035029 -> 0821*****029
     *
     * @param string|null $phone
     * @return string
     */
    function mask_phone_number(?string $phone): string
    {
        if (empty($phone)) {
            return '';
        }

        $phone = preg_replace('/\D/', '', $phone);
        $len = strlen($phone);

        if ($len <= 7) {
            // Too short to mask meaningfully, mask all but first 2 and last 2
            return substr($phone, 0, 2) . str_repeat('*', max($len - 4, 0)) . substr($phone, -2);
        }

        return substr($phone, 0, 4) . str_repeat('*', $len - 7) . substr($phone, -3);
    }
}

if (!function_exists('mask_name')) {
    /**
     * Mask a person's name for public display, keeping first and last letter of each word.
     * Example: Budi Santoso -> B**i S*****o
     *
     * @param string|null $name
     * @return string
     */
    function mask_name(?string $name): string
    {
        if (empty($name)) {
            return '';
        }

        $words = explode(' ', trim($name));
        $masked = [];

        foreach ($words as $word) {
            $len = mb_strlen($word);

            if ($len <= 2) {
                $masked[] = $word;
            } else {
                $first = mb_substr($word, 0, 1);
                $last = mb_substr($word, -1);
                $masked[] = $first . str_repeat('*', $len - 2) . $last;
            }
        }

        return implode(' ', $masked);
    }
}

if (!function_exists('get_qris_config')) {
    /**
     * Get QRIS and non-cash payment configuration from Settings.
     */
    function get_qris_config(): array
    {
        $merchantName = \App\Models\Setting::getValue('qris_merchant_name', 'BENING LAUNDRY - SMKN 1 CIAMIS');
        $nmid = \App\Models\Setting::getValue('qris_nmid', 'ID1026000109281');
        $qrisImage = \App\Models\Setting::getValue('qris_image', null);
        $qrisPayload = \App\Models\Setting::getValue('qris_payload', '');
        $bankInfo = \App\Models\Setting::getValue('bank_transfer_info', 'Bank BJB / BCA - Unit Produksi Perhotelan SMKN 1 Ciamis');

        $defaultPayload = $qrisPayload ?: "QRIS:{$merchantName}|NMID:{$nmid}";
        $fallbackQrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=320x320&margin=8&data=' . urlencode($defaultPayload);

        return [
            'merchant_name' => $merchantName,
            'nmid' => $nmid,
            'qris_image' => $qrisImage,
            'qris_payload' => $qrisPayload,
            'bank_info' => $bankInfo,
            'display_qr_url' => $qrisImage ?: $fallbackQrUrl,
        ];
    }
}

if (!function_exists('classify_payment_method')) {
    /**
     * Classify a payment method string into 'tunai', 'qris', or 'transfer'.
     */
    function classify_payment_method(?string $method): string
    {
        $m = strtolower(trim((string) $method));
        if ($m === '' || in_array($m, ['tunai', 'cash'])) {
            return 'tunai';
        }
        if (str_contains($m, 'qris')) {
            return 'qris';
        }
        return 'transfer';
    }
}

if (!function_exists('calculate_payment_recap')) {
    /**
     * Calculate comprehensive payment recap by method (Tunai, QRIS, Transfer) and status.
     */
    function calculate_payment_recap($lunasTransactions, $belumBayarTransactions = null): array
    {
        $lunas = collect($lunasTransactions);
        $belumBayar = $belumBayarTransactions ? collect($belumBayarTransactions) : collect();

        $totalPendapatan = (int) $lunas->sum('total_price');
        $totalTransaksi = $lunas->count();

        $tunaiItems = $lunas->filter(fn($t) => classify_payment_method($t->payment_method) === 'tunai');
        $qrisItems = $lunas->filter(fn($t) => classify_payment_method($t->payment_method) === 'qris');
        $transferItems = $lunas->filter(fn($t) => classify_payment_method($t->payment_method) === 'transfer');

        $tunaiNominal = (int) $tunaiItems->sum('total_price');
        $qrisNominal = (int) $qrisItems->sum('total_price');
        $transferNominal = (int) $transferItems->sum('total_price');
        $nonTunaiNominal = $qrisNominal + $transferNominal;

        $tunaiCount = $tunaiItems->count();
        $qrisCount = $qrisItems->count();
        $transferCount = $transferItems->count();
        $nonTunaiCount = $qrisCount + $transferCount;

        $tunaiPersen = $totalPendapatan > 0 ? round(($tunaiNominal / $totalPendapatan) * 100, 1) : 0;
        $qrisPersen = $totalPendapatan > 0 ? round(($qrisNominal / $totalPendapatan) * 100, 1) : 0;
        $transferPersen = $totalPendapatan > 0 ? round(($transferNominal / $totalPendapatan) * 100, 1) : 0;
        $nonTunaiPersen = $totalPendapatan > 0 ? round(($nonTunaiNominal / $totalPendapatan) * 100, 1) : 0;

        $piutangNominal = (int) $belumBayar->sum('total_price');
        $piutangCount = $belumBayar->count();

        return [
            'total_pendapatan' => $totalPendapatan,
            'total_transaksi' => $totalTransaksi,
            'tunai_nominal' => $tunaiNominal,
            'tunai_count' => $tunaiCount,
            'tunai_persen' => $tunaiPersen,
            'qris_nominal' => $qrisNominal,
            'qris_count' => $qrisCount,
            'qris_persen' => $qrisPersen,
            'transfer_nominal' => $transferNominal,
            'transfer_count' => $transferCount,
            'transfer_persen' => $transferPersen,
            'non_tunai_nominal' => $nonTunaiNominal,
            'non_tunai_count' => $nonTunaiCount,
            'non_tunai_persen' => $nonTunaiPersen,
            'piutang_nominal' => $piutangNominal,
            'piutang_count' => $piutangCount,
            'methods' => [
                [
                    'key' => 'tunai',
                    'label' => 'Tunai (Cash)',
                    'count' => $tunaiCount,
                    'total' => $tunaiNominal,
                    'persen' => $tunaiPersen,
                ],
                [
                    'key' => 'qris',
                    'label' => 'QRIS',
                    'count' => $qrisCount,
                    'total' => $qrisNominal,
                    'persen' => $qrisPersen,
                ],
                [
                    'key' => 'transfer',
                    'label' => 'Transfer Bank / E-Wallet',
                    'count' => $transferCount,
                    'total' => $transferNominal,
                    'persen' => $transferPersen,
                ],
            ],
        ];
    }
}
