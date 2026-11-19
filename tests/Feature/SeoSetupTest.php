<?php

namespace Tests\Feature;

use App\Models\Layanan;
use App\Models\Setting;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoSetupTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_renders_seo_meta_tags_open_graph_and_json_ld_schema(): void
    {
        Layanan::create([
            'nama' => 'Cuci Komplit Express Ciamis',
            'kategori' => 'kiloan',
            'harga' => 8000,
            'estimasi' => 24,
            'status' => true,
        ]);

        Setting::setValue('seo_google_verification', 'test-gsc-token-123');

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('<meta name="description"', false);
        $response->assertSee('<meta name="keywords"', false);
        $response->assertSee('<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">', false);
        $response->assertSee('<link rel="canonical"', false);
        $response->assertSee('<meta name="google-site-verification" content="test-gsc-token-123"', false);
        $response->assertSee('property="og:title"', false);
        $response->assertSee('property="og:description"', false);
        $response->assertSee('property="og:image"', false);
        $response->assertSee('name="twitter:card"', false);
        $response->assertSee('<link rel="icon"', false);
        $response->assertSee('<h1', false);
        $response->assertSee('<script type="application/ld+json">', false);
        $response->assertSee('"DryCleaningOrLaundry"', false);
        $response->assertSee('Cuci Komplit Express Ciamis', false);
    }

    public function test_robots_txt_is_served_dynamically_with_disallow_rules_and_sitemap(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertStatus(200);
        $this->assertStringContainsString('text/plain', (string) $response->headers->get('Content-Type'));

        $content = $response->getContent();
        $this->assertStringContainsString('User-agent: *', $content);
        $this->assertStringContainsString('Disallow: /admin', $content);
        $this->assertStringContainsString('Disallow: /petugas', $content);
        $this->assertStringContainsString('Disallow: /login', $content);
        $this->assertStringContainsString('Disallow: /track', $content);
        $this->assertStringContainsString('Sitemap: ' . url('/sitemap.xml'), $content);
    }

    public function test_sitemap_xml_is_valid_xml_containing_public_landing_url(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $this->assertStringContainsString('xml', (string) $response->headers->get('Content-Type'));

        $content = $response->getContent();
        $this->assertStringContainsString('<urlset', $content);
        $this->assertStringContainsString('<loc>' . e(url('/')) . '</loc>', $content);
        $this->assertStringContainsString('<priority>1.0</priority>', $content);
        $this->assertStringNotContainsString('/admin', $content);
        $this->assertStringNotContainsString('/petugas', $content);
    }

    public function test_private_and_operational_pages_emit_noindex_meta_tag(): void
    {
        $loginResponse = $this->get('/login');
        $loginResponse->assertStatus(200);
        $loginResponse->assertSee('<meta name="robots" content="noindex, nofollow"', false);

        $staff = User::factory()->create(['role' => 'petugas_piket']);
        Transaksi::create([
            'transaksi_code' => 'TRX-SEO-001',
            'user_id' => $staff->id,
            'kasir_name' => 'Kasir SEO',
            'customer_name' => 'Pelanggan SEO',
            'customer_phone' => '081234567890',
            'service_type' => 'Cuci Kering',
            'weight' => 2.5,
            'price_per_kg' => 7000,
            'total_price' => 17500,
            'status' => 'washing',
            'payment_status' => 'unpaid',
        ]);

        $trackResponse = $this->get('/track?nota_number=TRX-SEO-001');
        $trackResponse->assertStatus(200);
        $trackResponse->assertSee('<meta name="robots" content="noindex, nofollow"', false);
    }

    public function test_super_admin_and_admin_can_view_and_update_seo_settings(): void
    {
        foreach (['super_admin', 'admin'] as $index => $role) {
            $actor = User::factory()->create([
                'name' => 'Actor ' . $role,
                'role' => $role,
            ]);

            $page = $this->actingAs($actor)->get(route('admin.settings'));
            $page->assertStatus(200);
            $page->assertSee('id="seo-config"', false);
            $page->assertSee(route('admin.settings.seo'));

            $customTitle = 'Bening Laundry Ciamis Custom Title #' . $index;
            $customDesc = 'Deskripsi layanan laundry terbaik di Ciamis untuk pengujian SEO #' . $index . '.';
            $customKeywords = 'laundry ciamis, cuci kilat ciamis, smkn 1 ciamis #' . $index;
            $rawVerificationTag = '<meta name="google-site-verification" content="token-verifikasi-' . $index . '" />';

            $postResponse = $this->actingAs($actor)->post(route('admin.settings.seo'), [
                'seo_title' => $customTitle,
                'seo_description' => $customDesc,
                'seo_keywords' => $customKeywords,
                'seo_google_verification' => $rawVerificationTag,
            ]);

            $postResponse->assertRedirect(route('admin.settings') . '#seo-config');
            $postResponse->assertSessionHas('success');

            $seoConfig = get_seo_config();
            $this->assertSame($customTitle, $seoConfig['title']);
            $this->assertSame($customDesc, $seoConfig['description']);
            $this->assertSame($customKeywords, $seoConfig['keywords']);
            $this->assertSame('token-verifikasi-' . $index, $seoConfig['google_verification']);

            $landing = $this->get('/');
            $landing->assertStatus(200);
            $landing->assertSee('<title>' . $customTitle . '</title>', false);
            $landing->assertSee('content="' . $customDesc . '"', false);
            $landing->assertSee('content="token-verifikasi-' . $index . '"', false);
        }
    }

    public function test_non_admin_roles_cannot_update_seo_settings(): void
    {
        $staff = User::factory()->create([
            'role' => 'staff',
            'division' => 'washing',
        ]);
        $staffResponse = $this->actingAs($staff)->post(route('admin.settings.seo'), [
            'seo_title' => 'Attempted Staff SEO Title',
            'seo_description' => 'Attempted Staff SEO Description',
        ]);
        $staffResponse->assertRedirect(route('petugas_piket.dashboard'));

        $customer = User::factory()->create(['role' => 'customer']);
        $customerResponse = $this->actingAs($customer)->post(route('admin.settings.seo'), [
            'seo_title' => 'Attempted Customer SEO Title',
            'seo_description' => 'Attempted Customer SEO Description',
        ]);
        $customerResponse->assertStatus(403);
    }
}
