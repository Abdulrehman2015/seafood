<?php

namespace Tests\Feature;

use App\Models\PageSeo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageSeoTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $retailUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email'          => 'admin_seo@oceanfresh.com',
            'customer_group' => 'admin',
        ]);

        $this->retailUser = User::factory()->create([
            'email'          => 'user_seo@oceanfresh.com',
            'customer_group' => 'retail',
        ]);
    }

    /**
     * Test admin can access page SEO index and default pages are seeded.
     */
    public function test_admin_can_access_page_seo_index(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.page-seo.index'));

        $response->assertStatus(200);
        $response->assertSee('Page SEO');
        $response->assertSee('About Us');
        $response->assertSee('Shop / Products');
        $response->assertSee('Contact Us');
        $response->assertSee('English Title');
    }

    /**
     * Test non-admin cannot access page SEO.
     */
    public function test_non_admin_cannot_access_page_seo(): void
    {
        $response = $this->actingAs($this->retailUser)->get(route('admin.page-seo.index'));
        $response->assertStatus(403);
    }

    /**
     * Test admin can view create page SEO form.
     */
    public function test_admin_can_view_create_page_seo_form(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.page-seo.create'));

        $response->assertStatus(200);
        $response->assertSee('Create Page SEO');
        $response->assertSee('Select Page');
        $response->assertSee('English SEO');
        $response->assertSee('Meta Title');
        $response->assertSee('Shared SEO');
    }

    /**
     * Test admin can store a new custom page SEO.
     */
    public function test_admin_can_store_page_seo(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.page-seo.store'), [
            'page_name'        => 'Custom Logistics Policy',
            'page_slug'        => 'custom-logistics-policy',
            'meta_title'       => 'Custom Cold Chain Logistics Policy | OceanFresh',
            'meta_description' => 'Detailed cold chain and freight guidelines.',
            'meta_keywords'    => 'cold storage, refrigerated shipping',
            'canonical_url'    => 'https://oceanfresh.com/custom-logistics-policy',
        ]);

        $response->assertRedirect(route('admin.page-seo.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('page_seos', [
            'page_slug'  => 'custom-logistics-policy',
            'meta_title' => 'Custom Cold Chain Logistics Policy | OceanFresh',
        ]);
    }

    /**
     * Test admin can update an existing page SEO.
     */
    public function test_admin_can_update_page_seo(): void
    {
        PageSeo::ensureDefaults();
        $seo = PageSeo::where('page_slug', 'about')->firstOrFail();

        $response = $this->actingAs($this->admin)->put(route('admin.page-seo.update', $seo), [
            'page_name'        => 'About Us',
            'page_slug'        => 'about',
            'meta_title'       => 'About CTDC | Global Research & Consultancy',
            'meta_description' => 'Updated sustainable supply description.',
            'meta_keywords'    => 'seafood, sustainable, ocean',
            'canonical_url'    => 'https://oceanfresh.com/about',
        ]);

        $response->assertRedirect(route('admin.page-seo.index'));
        $response->assertSessionHas('success');

        $this->assertEquals('About CTDC | Global Research & Consultancy', $seo->fresh()->meta_title);
    }

    /**
     * Test public page uses dynamic Page SEO meta tags.
     */
    public function test_public_page_uses_dynamic_page_seo(): void
    {
        PageSeo::create([
            'page_name'        => 'Contact Us',
            'page_slug'        => 'contact',
            'meta_title'       => 'Contact Worldwide Headquarters | OceanFresh VIP',
            'meta_description' => 'Direct access to seafood procurement executives.',
            'meta_keywords'    => 'exclusive contact, seafood headquarters',
            'canonical_url'    => 'https://oceanfresh.com/contact-vip',
        ]);

        $response = $this->get(route('contact'));

        $response->assertStatus(200);
        $response->assertSee('Contact Worldwide Headquarters | OceanFresh VIP');
        $response->assertSee('Direct access to seafood procurement executives.');
        $response->assertSee('exclusive contact, seafood headquarters');
    }
}
