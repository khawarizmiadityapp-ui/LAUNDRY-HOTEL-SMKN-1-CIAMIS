<?php

namespace App\Services;

class Google2FAService
{
    private static array $base32Lookup = [
        'A' => 0, 'B' => 1, 'C' => 2, 'D' => 3, 'E' => 4, 'F' => 5, 'G' => 6, 'H' => 7,
        'I' => 8, 'J' => 9, 'K' => 10, 'L' => 11, 'M' => 12, 'N' => 13, 'O' => 14, 'P' => 15,
        'Q' => 16, 'R' => 17, 'S' => 18, 'T' => 19, 'U' => 20, 'V' => 21, 'W' => 22, 'X' => 23,
        'Y' => 24, 'Z' => 25, '2' => 26, '3' => 27, '4' => 28, '5' => 29, '6' => 30, '7' => 31,
    ];

    private static string $base32Alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    /**
     * Generate a cryptographically secure Base32 secret key (10 bytes = 16 Base32 characters, standard RFC 4226 / Google Authenticator).
     */
    public static function generateSecretKey(int $length = 10): string
    {
        $randomBytes = random_bytes($length);
        return self::base32Encode($randomBytes);
    }

    /**
     * Calculate 6-digit TOTP for given Base32 secret key and optional timestamp.
     */
    public static function calculateOtp(string $secret, ?int $timestamp = null): string
    {
        $secret = strtoupper(str_replace([' ', '-'], '', $secret));
        $timestamp = $timestamp ?? time();
        $timeSlice = (int) floor($timestamp / 30);

        $binarySecret = self::base32Decode($secret);
        if ($binarySecret === '') {
            return '000000';
        }

        // Pack 64-bit integer into big-endian byte sequence (RFC 6238)
        $packedTime = pack('N*', 0) . pack('N*', $timeSlice);
        $hash = hash_hmac('sha1', $packedTime, $binarySecret, true);

        // Dynamic truncation (RFC 4226)
        $offset = ord($hash[19]) & 0x0F;
        $otp = ((ord($hash[$offset]) & 0x7F) << 24)
            | ((ord($hash[$offset + 1]) & 0xFF) << 16)
            | ((ord($hash[$offset + 2]) & 0xFF) << 8)
            | (ord($hash[$offset + 3]) & 0xFF);

        $otp = $otp % 1000000;

        return str_pad((string) $otp, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Verify a 6-digit OTP code against the secret key with time drift tolerance window.
     */
    public static function verifyKey(string $secret, string $otp, int $window = 1): bool
    {
        $secret = strtoupper(str_replace([' ', '-'], '', $secret));
        $otp = preg_replace('/\s+/', '', (string) $otp);

        if (strlen($otp) !== 6 || !ctype_digit($otp)) {
            return false;
        }

        $currentTime = time();

        for ($i = -$window; $i <= $window; $i++) {
            $calculatedOtp = self::calculateOtp($secret, $currentTime + ($i * 30));
            if (hash_equals($calculatedOtp, $otp)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get otpauth:// URI for QR code generation.
     */
    public static function getQrCodeUrl(string $company, string $holder, string $secret): string
    {
        $secret = strtoupper(str_replace([' ', '-'], '', $secret));
        $encodedCompany = rawurlencode($company);
        $encodedHolder = rawurlencode($holder);

        return "otpauth://totp/{$encodedCompany}:{$encodedHolder}?secret={$secret}&issuer={$encodedCompany}&algorithm=SHA1&digits=6&period=30";
    }

    /**
     * Get image URL for rendering QR Code.
     */
    public static function getQrCodeImageUrl(string $company, string $holder, string $secret): string
    {
        $qrUrl = self::getQrCodeUrl($company, $holder, $secret);
        return 'https://api.qrserver.com/v1/create-qr-code/?size=240x240&data=' . urlencode($qrUrl);
    }

    /**
     * Format secret key into grouped 4-char chunks for clean manual input.
     */
    public static function formatSecretKey(string $secret): string
    {
        $clean = strtoupper(str_replace([' ', '-'], '', $secret));
        return trim(chunk_split($clean, 4, ' '));
    }

    /**
     * Base32 encoding (RFC 4648).
     */
    public static function base32Encode(string $data): string
    {
        if (empty($data)) {
            return '';
        }

        $binary = '';
        $length = strlen($data);
        for ($i = 0; $i < $length; $i++) {
            $binary .= str_pad(decbin(ord($data[$i])), 8, '0', STR_PAD_LEFT);
        }

        $chunks = str_split($binary, 5);
        $encoded = '';
        foreach ($chunks as $chunk) {
            $chunk = str_pad($chunk, 5, '0', STR_PAD_RIGHT);
            $index = bindec($chunk);
            $encoded .= self::$base32Alphabet[$index];
        }

        return $encoded;
    }

    /**
     * Base32 decoding (RFC 4648).
     */
    public static function base32Decode(string $secret): string
    {
        $secret = strtoupper(str_replace([' ', '-', '='], '', $secret));
        if (empty($secret)) {
            return '';
        }

        $binary = '';
        $length = strlen($secret);
        for ($i = 0; $i < $length; $i++) {
            $char = $secret[$i];
            if (!isset(self::$base32Lookup[$char])) {
                continue;
            }
            $binary .= str_pad(decbin(self::$base32Lookup[$char]), 5, '0', STR_PAD_LEFT);
        }

        $chunks = str_split($binary, 8);
        $decoded = '';
        foreach ($chunks as $chunk) {
            if (strlen($chunk) === 8) {
                $decoded .= chr(bindec($chunk));
            }
        }

        return $decoded;
    }
}
