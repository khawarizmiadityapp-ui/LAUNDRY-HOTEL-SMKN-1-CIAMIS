<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class WhatsAppHelperTest extends TestCase
{
    /**
     * Test the WhatsApp formatting helper function
     *
     * @return void
     */
    public function test_format_whatsapp_number()
    {
        // Load the helper file if not loaded
        if (!function_exists('format_whatsapp_number')) {
            require_once __DIR__ . '/../../app/helpers.php';
        }

        // Test null or empty
        $this->assertEquals('', format_whatsapp_number(null));
        $this->assertEquals('', format_whatsapp_number(''));
        
        // Test standard '08...' format
        $this->assertEquals('6282116035029', format_whatsapp_number('082116035029'));
        
        // Test '628...' format
        $this->assertEquals('6282116035029', format_whatsapp_number('6282116035029'));
        
        // Test '+628...' format
        $this->assertEquals('6282116035029', format_whatsapp_number('+6282116035029'));
        
        // Test '8...' format
        $this->assertEquals('6282116035029', format_whatsapp_number('82116035029'));
        
        // Test '6208...' format (mistake commonly made when mixing country code and local prefix)
        $this->assertEquals('6282116035029', format_whatsapp_number('62082116035029'));
        
        // Test with non-numeric characters (hyphens, spaces, brackets)
        $this->assertEquals('6282116035029', format_whatsapp_number('0821-1603-5029'));
        $this->assertEquals('6282116035029', format_whatsapp_number('+62 821 1603 5029'));
        $this->assertEquals('6282116035029', format_whatsapp_number('(0821) 1603-5029'));
    }

    /**
     * Test the WhatsApp formatting helper function against malicious inputs (Security)
     *
     * @return void
     */
    public function test_security_format_whatsapp_number()
    {
        // Load the helper file if not loaded
        if (!function_exists('format_whatsapp_number')) {
            require_once __DIR__ . '/../../app/helpers.php';
        }

        // Test SQL Injection payloads
        $this->assertEquals('11', format_whatsapp_number("' OR 1=1 --"));
        $this->assertEquals('6282116035029', format_whatsapp_number("082116035029'; DROP TABLE users; --"));

        // Test XSS payloads
        $this->assertEquals('', format_whatsapp_number("<script>alert('xss')</script>"));
        $this->assertEquals('6282116035029', format_whatsapp_number("<script>alert('xss')</script>082116035029"));

        // Test alphabet characters completely removed
        $this->assertEquals('6282116035029', format_whatsapp_number("ABCD0821XYZ1603EFG5029H"));

        // Test extremely long string (buffer-like) to ensure regex engine doesn't break
        $longPayload = str_repeat("A", 10000) . "082116035029" . str_repeat("B", 10000);
        $this->assertEquals('6282116035029', format_whatsapp_number($longPayload));
    }
}
