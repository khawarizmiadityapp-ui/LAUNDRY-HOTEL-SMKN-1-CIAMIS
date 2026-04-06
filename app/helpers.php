<?php

if (! function_exists('rupiah')) {
    /**
     * Format integer ke format Rupiah.
     *
     * @param  int|float  $nominal
     * @param  bool       $withPrefix   Tampilkan "Rp" di depan
     * @param  bool       $withCents    Tampilkan desimal (,00)
     * @return string
     */
    function rupiah(int|float $nominal, bool $withPrefix = true, bool $withCents = false): string
    {
        $formatted = number_format($nominal, $withCents ? 2 : 0, ',', '.');
        return ($withPrefix ? 'Rp' : '') . $formatted;
    }
}

if (! function_exists('rupiah_plain')) {
    /**
     * Format tanpa prefix "Rp".
     */
    function rupiah_plain(int|float $nominal): string
    {
        return rupiah($nominal, false);
    }
}