<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SettingsManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $retailUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email'          => 'admin_settings@oceanfresh.com',
            'customer_group' => 'admin',
        ]);

        $this->retailUser = User::factory()->create([
            'email'          => 'retail_user@oceanfresh.com',
            'customer_group' => 'retail',
        ]);
    }

    /**
     * Test admin can access settings management page.
     */
    public function test_admin_can_access_settings_management(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.settings.index'));

        $response->assertStatus(200);
        $response->assertSee('Settings Management');
        $response->assertSee('General Settings');
        $response->assertSee('SMTP Settings');
        $response->assertSee('Modules Settings');
        $response->assertSee('Website Tracking');
        $response->assertSee('Site Appearance');
        $response->assertSee('reCAPTCHA Settings');
        $response->assertSee('Site Keys');
    }

    /**
     * Test non-admin cannot access settings.
     */
    public function test_non_admin_cannot_access_settings(): void
    {
        $response = $this->actingAs($this->retailUser)->get(route('admin.settings.index'));
        $response->assertStatus(403);
    }

    /**
     * Test updating General Settings.
     */
    public function test_admin_can_update_general_settings(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.settings.update'), [
            'tab'                     => 'general',
            'site_name'               => 'OceanFresh International Trading',
            'site_description'        => 'Global seafood supplier & distribution network.',
            'meta_keywords'           => 'frozen salmon, prawns, wholesale seafood',
            'canonical_url'           => 'https://oceanfresh.com',
            'header_tags'             => '<meta name="custom-test" content="123">',
            'footer_tags'             => '<script>console.log("footer");</script>',
            'schema_markup'           => '{"@context":"https://schema.org"}',
        ]);

        $response->assertRedirect(route('admin.settings.index', ['tab' => 'general']));
        $response->assertSessionHas('success');

        $this->assertEquals('OceanFresh International Trading', Setting::get('site_name'));
        $this->assertEquals('Global seafood supplier & distribution network.', Setting::get('site_description'));
        $this->assertEquals('https://oceanfresh.com', Setting::get('canonical_url'));
        $this->assertEquals('<meta name="custom-test" content="123">', Setting::get('header_tags'));
    }

    /**
     * Test updating SMTP Settings.
     */
    public function test_admin_can_update_smtp_settings(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.settings.update'), [
            'tab'                  => 'smtp',
            'mail_mailer'          => 'smtp',
            'mail_host'            => 'smtp.mailtrap.io',
            'mail_port'            => '2525',
            'mail_encryption'      => 'TLS',
            'mail_username'        => 'mailtrap_user_123',
            'mail_password'        => 'secret_pass_123',
            'mail_from_address'    => 'orders@oceanfresh.com',
            'mail_from_name'       => 'OceanFresh Orders',
            'mail_contact_email'   => 'help@oceanfresh.com',
            'mail_secondary_email' => 'ops@oceanfresh.com',
        ]);

        $response->assertRedirect(route('admin.settings.index', ['tab' => 'smtp']));
        $response->assertSessionHas('success');

        $this->assertEquals('smtp.mailtrap.io', Setting::get('mail_host'));
        $this->assertEquals('2525', Setting::get('mail_port'));
        $this->assertEquals('TLS', Setting::get('mail_encryption'));
        $this->assertEquals('orders@oceanfresh.com', Setting::get('mail_from_address'));
    }

    /**
     * Test updating Modules Settings.
     */
    public function test_admin_can_update_modules_settings(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.settings.update'), [
            'tab'                        => 'modules',
            'module_walkin_flow'         => '1',
            'module_wholesale_approval'  => '1',
            // Omit module_rfq_trading to test toggle turning off
        ]);

        $response->assertRedirect(route('admin.settings.index', ['tab' => 'modules']));

        $this->assertEquals('1', Setting::get('module_walkin_flow'));
        $this->assertEquals('1', Setting::get('module_wholesale_approval'));
        $this->assertEquals('0', Setting::get('module_rfq_trading'));
    }

    /**
     * Test sending test email from settings.
     */
    public function test_admin_can_trigger_test_email(): void
    {
        Mail::fake();

        $response = $this->actingAs($this->admin)->post(route('admin.settings.testEmail'), [
            'email' => 'test_receiver@example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }
}
