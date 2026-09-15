<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Services\PricingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::create([
            'name'        => 'Fresh Fish',
            'slug'        => 'fresh-fish',
            'is_active'   => true,
            'sort_order'  => 1,
        ]);

        $this->product = Product::create([
            'name'                => 'Norwegian Salmon Fillet',
            'slug'                => 'norwegian-salmon-fillet',
            'category_id'         => $this->category->id,
            'retail_price'        => 58.00,
            'walkin_price'        => 52.00,
            'wholesale_price'     => 42.00,
            'trading_price'       => 35.00,
            'unit'                => 'kg',
            'stock_quantity'      => 100,
            'track_stock'         => true,
            'moq'                 => 1,
            'moq_wholesale'       => 5,
            'moq_trading'         => 50,
            'is_active'           => true,
            'is_walkin_available' => true,
        ]);
    }

    public function test_security_headers_are_present_on_responses(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $this->assertTrue($response->headers->has('Content-Security-Policy'));
        $this->assertTrue($response->headers->has('Permissions-Policy'));
    }

    public function test_unapproved_wholesale_customer_cannot_see_wholesale_pricing(): void
    {
        $pendingUser = User::create([
            'name'            => 'Pending Wholesale Ltd',
            'email'           => 'pending@wholesale.com',
            'password'        => bcrypt('Secret1234'),
            'customer_group'  => 'wholesale',
            'approval_status' => 'pending',
            'phone'           => '0123456789',
            'company_name'    => 'Pending Wholesale Sdn Bhd',
            'address'         => '123 Business St',
            'city'            => 'KL',
            'state'           => 'KL',
            'postcode'        => '50000',
        ]);

        $this->actingAs($pendingUser);

        $pricingService = app(PricingService::class);
        $group = $pricingService->resolveGroup();

        // Must resolve to retail, preventing premature wholesale discount exposure
        $this->assertEquals('retail', $group);
        $this->assertEquals(58.00, $pricingService->getPrice($this->product, $group));
    }

    public function test_inactive_product_returns_404(): void
    {
        $this->product->update(['is_active' => false]);

        $response = $this->get(route('shop.show', $this->product->slug));
        $response->assertStatus(404);
    }

    public function test_guest_order_cannot_be_viewed_by_other_visitors_idor_prevention(): void
    {
        $order = Order::create([
            'user_id'               => null,
            'customer_group'        => 'walkin',
            'customer_name'         => 'Guest Shopper',
            'customer_phone'        => '0123456789',
            'status'                => 'confirmed',
            'payment_status'        => 'paid',
            'fulfillment_type'      => 'self_collection',
            'subtotal'              => 52.00,
            'total'                 => 52.00,
            'stripe_payment_intent' => 'pi_test_123456',
        ]);

        // Unrelated visitor tries to access the order confirmation
        $response = $this->get(route('checkout.success', $order));
        $response->assertStatus(403);

        // Visitor with matching session can view it
        $authorizedResponse = $this->withSession(['last_placed_order_id' => $order->id])
            ->get(route('checkout.success', $order));
        $authorizedResponse->assertStatus(200);
    }

    public function test_payment_intent_replay_attack_is_rejected(): void
    {
        // Pre-existing order with this payment_intent_id
        Order::create([
            'user_id'               => null,
            'customer_group'        => 'walkin',
            'customer_name'         => 'Legit Customer',
            'customer_phone'        => '0123456789',
            'status'                => 'confirmed',
            'payment_status'        => 'paid',
            'fulfillment_type'      => 'self_collection',
            'subtotal'              => 52.00,
            'total'                 => 52.00,
            'stripe_payment_intent' => 'pi_duplicate_token_999',
        ]);

        $user = User::factory()->create();
        $this->actingAs($user);

        // Put an item in cart
        $cartService = app(\App\Services\CartService::class);
        $cartService->add($this->product->id, 1);

        // Attempt to place another order with the same payment intent
        $response = $this->post(route('checkout.store'), [
            'fulfillment_type'  => 'self_collection',
            'payment_intent_id' => 'pi_duplicate_token_999',
            'customer_name'     => 'Attacker Replay',
            'customer_phone'    => '0199999999',
        ]);

        $response->assertSessionHas('error', 'This payment reference has already been used for an existing order.');
        // Verify no second order with this intent was created
        $this->assertEquals(1, Order::where('stripe_payment_intent', 'pi_duplicate_token_999')->count());
    }

    public function test_cart_update_with_invalid_id_handles_gracefully(): void
    {
        $response = $this->patchJson(route('cart.update', 99999), [
            'quantity' => 2,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => false,
            'message' => 'Cart item not found.',
        ]);
    }

    public function test_contact_endpoint_rate_limiting(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $response = $this->post(route('contact.submit'), [
                'name'    => 'Test User',
                'email'   => 'test@example.com',
                'message' => 'Hello test message',
            ]);
            $response->assertStatus(302);
        }

        // 6th attempt should exceed 5 per minute threshold
        $blockedResponse = $this->post(route('contact.submit'), [
            'name'    => 'Spammer',
            'email'   => 'spam@example.com',
            'message' => 'Spam message',
        ]);

        $blockedResponse->assertStatus(429);
    }
}
