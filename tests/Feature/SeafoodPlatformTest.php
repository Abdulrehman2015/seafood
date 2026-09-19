<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\PricingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeafoodPlatformTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_retail_user_can_view_catalogue_with_retail_price(): void
    {
        $category = Category::create(['name' => 'Fish', 'slug' => 'fish', 'is_active' => true]);
        $product = Product::create([
            'name'                => 'Norwegian Salmon Fillet',
            'slug'                => 'norwegian-salmon-fillet',
            'category_id'         => $category->id,
            'unit'                => 'pack',
            'retail_price'        => 65.00,
            'walkin_price'        => 58.00,
            'wholesale_price'     => 48.00,
            'trading_price'       => 42.00,
            'moq'                 => 1,
            'moq_wholesale'       => 5,
            'moq_trading'         => 20,
            'stock_quantity'      => 50,
            'is_active'           => true,
            'is_walkin_available' => true,
        ]);

        $response = $this->get('/shop');
        $response->assertStatus(200);
        $response->assertSee('Norwegian Salmon Fillet');
        $response->assertSee('65.00'); // Retail price
    }

    public function test_walkin_catalogue_only_shows_walkin_available_products(): void
    {
        $category = Category::create(['name' => 'Shellfish', 'slug' => 'shellfish', 'is_active' => true]);

        // Product available for walk-in
        $pWalkin = Product::create([
            'name'                => 'Fresh Tiger Prawns',
            'slug'                => 'fresh-tiger-prawns',
            'category_id'         => $category->id,
            'unit'                => 'kg',
            'retail_price'        => 80.00,
            'walkin_price'        => 72.00,
            'wholesale_price'     => 60.00,
            'moq'                 => 1,
            'moq_wholesale'       => 5,
            'moq_trading'         => 20,
            'stock_quantity'      => 30,
            'is_active'           => true,
            'is_walkin_available' => true,
        ]);

        // Product NOT available for walk-in (export/wholesale only)
        $pWholesaleOnly = Product::create([
            'name'                => 'Bulk Container Scallops',
            'slug'                => 'bulk-container-scallops',
            'category_id'         => $category->id,
            'unit'                => 'carton',
            'retail_price'        => 500.00,
            'walkin_price'        => 500.00,
            'wholesale_price'     => 380.00,
            'moq'                 => 10,
            'moq_wholesale'       => 10,
            'moq_trading'         => 50,
            'stock_quantity'      => 100,
            'is_active'           => true,
            'is_walkin_available' => false,
        ]);

        // Scan QR code / enter walk-in
        $entryResponse = $this->get('/walkin/enter');
        $entryResponse->assertRedirect('/walkin');

        // Access walk-in shop with walk-in session
        $response = $this->withSession(['walkin_session' => true])->get('/walkin');
        $response->assertStatus(200);
        $response->assertSee('Fresh Tiger Prawns');
        $response->assertSee('72.00'); // Walk-in price
        $response->assertDontSee('Bulk Container Scallops'); // Not in walk-in
    }

    public function test_wholesale_registration_requires_company_and_is_pending_approval(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'Chef Roberto',
            'email'                 => 'roberto@searestaurant.com',
            'password'              => 'SecretPassword123!',
            'password_confirmation' => 'SecretPassword123!',
            'customer_group'        => 'wholesale',
            'phone'                 => '+60 12-999 8888',
            'company_name'          => 'Oceanic Bistro Sdn Bhd',
            'company_reg_no'        => '202301012345',
            'business_type'         => 'Restaurant & Catering',
            'address'               => '45, Jalan Telawi 3, Bangsar',
            'city'                  => 'Kuala Lumpur',
            'state'                 => 'Wilayah Persekutuan',
            'postcode'              => '59100',
        ]);

        $response->assertRedirect(route('otp.verify'));
        $this->assertDatabaseHas('users', [
            'email'           => 'roberto@searestaurant.com',
            'customer_group'  => 'wholesale',
            'approval_status' => 'pending',
            'company_name'    => 'Oceanic Bistro Sdn Bhd',
        ]);
    }

    public function test_admin_can_approve_pending_wholesale_customer(): void
    {
        $admin = User::create([
            'name'            => 'Store Admin',
            'email'           => 'admin@oceanfresh.com',
            'password'        => bcrypt('AdminPassword123!'),
            'customer_group'  => 'admin',
            'approval_status' => 'approved',
            'is_admin'        => true,
        ]);

        $pendingCustomer = User::create([
            'name'            => 'Chef Tony',
            'email'           => 'tony@bistro.com',
            'password'        => bcrypt('Pass1234!'),
            'customer_group'  => 'wholesale',
            'approval_status' => 'pending',
            'company_name'    => 'Tony Seafood Trattoria',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.customers.approve', $pendingCustomer));
        $response->assertSessionHas('success');

        $pendingCustomer->refresh();
        $this->assertEquals('approved', $pendingCustomer->approval_status);
        $this->assertNotNull($pendingCustomer->approved_at);
    }

    public function test_trading_customer_can_submit_rfq_and_admin_can_respond(): void
    {
        $tradingUser = User::create([
            'name'            => 'Global Traders Ltd',
            'email'           => 'trader@globaltrade.com',
            'password'        => bcrypt('TraderPass123!'),
            'customer_group'  => 'trading',
            'approval_status' => 'approved',
        ]);

        $product = Product::create([
            'name'                => 'Giant Black Tiger Prawns 500g',
            'slug'                => 'giant-black-tiger-prawns',
            'unit'                => 'box',
            'retail_price'        => 55.00,
            'walkin_price'        => 50.00,
            'wholesale_price'     => 42.00,
            'trading_price'       => 35.00,
            'moq'                 => 1,
            'moq_wholesale'       => 10,
            'moq_trading'         => 100,
            'stock_quantity'      => 500,
            'is_active'           => true,
            'is_walkin_available' => true,
        ]);

        // Trading customer submits RFQ
        $response = $this->actingAs($tradingUser)->post(route('quotations.store'), [
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity'   => 200,
                    'notes'      => 'Export grade IQF packaging required',
                ],
            ],
            'customer_notes' => 'Port Klang reefer container delivery needed',
        ]);

        $this->assertDatabaseHas('quotations', [
            'user_id' => $tradingUser->id,
            'status'  => 'pending',
        ]);

        $quotation = \App\Models\Quotation::where('user_id', $tradingUser->id)->first();
        $qi = $quotation->items->first();

        // Admin responds to RFQ
        $admin = User::create([
            'name'            => 'Super Admin',
            'email'           => 'super@oceanfresh.com',
            'password'        => bcrypt('SuperPass123!'),
            'customer_group'  => 'admin',
            'approval_status' => 'approved',
            'is_admin'        => true,
        ]);

        $respondResponse = $this->actingAs($admin)->patch(route('admin.quotations.respond', $quotation), [
            'items' => [
                [
                    'quotation_item_id' => $qi->id,
                    'quoted_price'      => 33.50,
                ],
            ],
            'admin_notes' => 'Special container price approved by managing director.',
            'valid_until' => now()->addDays(14)->format('Y-m-d'),
        ]);

        $respondResponse->assertRedirect(route('admin.quotations.index'));
        $quotation->refresh();
        $this->assertEquals('quoted', $quotation->status);
        $this->assertEquals(6700.00, (float) $quotation->total_quoted);
    }

    public function test_admin_can_view_walkin_qr(): void
    {
        $admin = User::create([
            'name'            => 'Store Admin',
            'email'           => 'admin2@oceanfresh.com',
            'password'        => bcrypt('AdminPassword123!'),
            'customer_group'  => 'admin',
            'approval_status' => 'approved',
            'is_admin'        => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.walkin.qr'));
        $response->assertStatus(200);
        $response->assertSee('Store Walk-in QR Code');
        $response->assertSee('<svg', false);
    }
}
