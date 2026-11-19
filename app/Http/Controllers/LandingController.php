<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Layanan;
use App\Models\ServicePrice; // Untuk fallback harga
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Home / Landing Page
     */
    public function index()
    {
        $layanans = Layanan::aktif()->get();
        $heroImage = \App\Models\Setting::getValue('hero_image', 'https://images.unsplash.com/photo-1517677208171-0bc6725a3e60?q=80&w=800&auto=format&fit=crop');
        $logoImage = \App\Models\Setting::getValue('logo_image', asset('images/logobening.jpeg'));
        $seo = get_seo_config();

        return view('welcome', compact('layanans', 'heroImage', 'logoImage', 'seo'));
    }

    /**
     * Public XML Sitemap for search engines.
     */
    public function sitemap()
    {
        $latestLayanan = Layanan::aktif()->latest('updated_at')->first();
        $lastmod = $latestLayanan && $latestLayanan->updated_at
            ? $latestLayanan->updated_at->toAtomString()
            : now()->toAtomString();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
            . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n"
            . '    <url>' . "\n"
            . '        <loc>' . e(url('/')) . '</loc>' . "\n"
            . '        <lastmod>' . e($lastmod) . '</lastmod>' . "\n"
            . '        <changefreq>weekly</changefreq>' . "\n"
            . '        <priority>1.0</priority>' . "\n"
            . '    </url>' . "\n"
            . '</urlset>' . "\n";

        return response($xml, 200)->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    /**
     * Dynamic robots.txt with absolute sitemap URL and private route disallow rules.
     */
    public function robots()
    {
        $lines = [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin',
            'Disallow: /admin/*',
            'Disallow: /petugas',
            'Disallow: /petugas/*',
            'Disallow: /login',
            'Disallow: /login/*',
            'Disallow: /logout',
            'Disallow: /track',
            'Disallow: /pos',
            'Disallow: /pos/*',
            'Disallow: /customers',
            'Disallow: /customers/*',
            'Disallow: /export-transaksi',
            'Disallow: /export-transaksi-pdf',
            '',
            'Sitemap: ' . url('/sitemap.xml'),
            '',
        ];

        return response(implode("\n", $lines), 200)
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    /**
     * Public Order Tracking
     */
    public function trackStatus(Request $request)
    {
        $request->validate([
            'nota_number' => 'required|string|max:50',
        ]);

        $order = Transaksi::with(['tasks.petugas', 'details.layanan'])
            ->where('transaksi_code', $request->nota_number)
            ->first();

        if (!$order) {
            return back()->with('error', "Nomor nota/resi '{$request->nota_number}' tidak ditemukan.")
                ->withInput();
        }

        return view('pages.track-result', compact('order'));
    }
}
