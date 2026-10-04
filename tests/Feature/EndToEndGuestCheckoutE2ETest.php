<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EndToEndGuestCheckoutE2ETest extends TestCase
{
    use RefreshDatabase;

    protected Category $category;
    protected Product $productA;
    protected Product $productB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::create([
            'name'        => 'Fresh Catch',
            'slug'        => 'fresh-catch',
            'is_active'   => true,
            'description' => 'Fresh seafood products',
        ]);

        $this->productA = Product::create([
            'name'                 => 'Tiger Prawns Grade A',
            'slug'                 => 'tiger-prawns-grade-a',
            'sku'                  => 'TP-GRDA-01',
            'category_id'          => $this->category->id,
            'retail_price'         => 45.00,
            'walkin_price'         => 40.00,
            'wholesale_price'      => 35.00,
            'retail_moq'           => 1,
            'walkin_moq'           => 1,
            'wholesale_moq'        => 5,
            'stock_quantity'       => 50,
            'unit'                 => 'kg',
            'is_walkin_available'  => true,
            'is_active'            => true,
        ]);

        $this->productB = Product::create([
            'name'                 => 'Norwegian Salmon Fillet',
            'slug'                 => 'norwegian-salmon-fillet',
            'sku'                  => 'NS-FIL-02',
            'category_id'          => $this->category->id,
            'retail_price'         => 65.00,
            'walkin_price'         => 58.00,
            'wholesale_price'      => 50.00,
            'retail_moq'           => 1,
            'walkin_moq'           => 1,
            'wholesale_moq'        => 5,
            'stock_quantity'       => 50,
            'unit'                 => 'kg',
            'is_walkin_available'  => true,
            'is_active'            => true,
        ]);
    }

    /**
     * Test Step 1: Browse Products & Cart Delivery Threshold under RM100
     */
    public function test_step_1_cart_under_rm100_shows_threshold_progress_banner(): void
    {
        $cartItem = new Cart([
            'product_id'     => $this->productA->id,
            'quantity'       => 1,
            'customer_group' => 'retail',
        ]);
        $cartItem->setRelation('product', $this->productA);

        $cartMock = $this->mock(\App\Services\CartService::class);
        $cartMock->shouldReceive('getItems')->andReturn(collect([$cartItem]));
        $cartMock->shouldReceive('totals')->andReturn([
            'subtotal' => 45.00,
            'total'    => 45.00,
            'count'    => 1,
        ]);

        $cartRes = $this->get(route('cart.index'));
        $cartRes->assertStatus(200);
        $cartRes->assertSee('Tiger Prawns Grade A');
        $cartRes->assertSee('45.00');

        // Verify threshold notice (RM 100 Reference Threshold, RM 55.00 shortfall)
        $cartRes->assertSee('RM 100.00 Reference Threshold');
        $cartRes->assertSee('55.00');
    }

    /**
     * Test Step 2: Cart Delivery Threshold at or above RM100
     */
    public function test_step_2_cart_at_or_above_rm100_unlocks_free_delivery_banner(): void
    {
        $cartItemA = new Cart([
            'product_id'     => $this->productA->id,
            'quantity'       => 1,
            'customer_group' => 'retail',
        ]);
        $cartItemA->setRelation('product', $this->productA);

        $cartItemB = new Cart([
            'product_id'     => $this->productB->id,
            'quantity'       => 1,
            'customer_group' => 'retail',
        ]);
        $cartItemB->setRelation('product', $this->productB);

        $cartMock = $this->mock(\App\Services\CartService::class);
        $cartMock->shouldReceive('getItems')->andReturn(collect([$cartItemA, $cartItemB]));
        $cartMock->shouldReceive('totals')->andReturn([
            'subtotal' => 110.00,
            'total'    => 110.00,
            'count'    => 2,
        ]);

        $cartRes = $this->get(route('cart.index'));
        $cartRes->assertStatus(200);

        // Verify free delivery unlocked banner
        $cartRes->assertSee('Free Standard Delivery Unlocked!');
        $cartRes->assertSee('RM 100.00 Reference Threshold');
    }

    /**
     * Test Step 3: Guest Checkout Access & Form Validation
     */
    public function test_step_3_guest_checkout_accessible_without_auth_and_validates_required_fields(): void
    {
        $cartItem = new Cart([
            'product_id'     => $this->productA->id,
            'quantity'       => 1,
            'customer_group' => 'retail',
        ]);
        $cartItem->setRelation('product', $this->productA);

        $cartMock = $this->mock(\App\Services\CartService::class);
        $cartMock->shouldReceive('getItems')->andReturn(collect([$cartItem]));
        $cartMock->shouldReceive('totals')->andReturn([
            'subtotal' => 45.00,
            'total'    => 45.00,
        ]);

        // Access Checkout as Guest
        $checkoutRes = $this->get(route('checkout.index'));
        $checkoutRes->assertStatus(200);

        // Verify customer contact fields exist and no password / OTP fields
        $checkoutRes->assertSee('customer_name');
        $checkoutRes->assertSee('customer_phone');
        $checkoutRes->assertSee('customer_email');
        $checkoutRes->assertDontSee('Enter your Password');
        $checkoutRes->assertDontSee('Verify OTP');

        // Validation test: submit without customer contact info
        $invalidSubmit = $this->post(route('checkout.store'), [
            'fulfillment_type' => 'delivery',
        ]);

        $invalidSubmit->assertSessionHasErrors(['customer_name', 'customer_phone', 'customer_email']);
    }

    /**
     * Test Step 4: Walk-in / Self-Collection Flow, Cash Checkout, Order Confirmation & Account Linking
     */
    public function test_step_4_complete_end_to_end_walkin_and_guest_order_linking(): void
    {
        $guestEmail = 'wendy.test@mstseafood.com';
        $guestPhone = '0123456789';
        $guestName  = 'Wendy Test';

        $cartItem = new Cart([
            'product_id'     => $this->productA->id,
            'quantity'       => 2,
            'customer_group' => 'walkin',
        ]);
        $cartItem->setRelation('product', $this->productA);

        $cartMock = $this->mock(\App\Services\CartService::class);
        $cartMock->shouldReceive('getItems')->andReturn(collect([$cartItem]));
        $cartMock->shouldReceive('totals')->andReturn([
            'subtotal' => 80.00,
            'total'    => 80.00,
        ]);
        $cartMock->shouldReceive('clear')->andReturn(null);

        // Submit Walk-in Cash Order
        $submitRes = $this->withSession(['walkin_session' => true])
            ->post(route('checkout.store'), [
                'fulfillment_type' => 'self_collection',
                'customer_name'    => $guestName,
                'customer_phone'   => $guestPhone,
                'customer_email'   => $guestEmail,
                'payment_method'   => 'cash',
                'collection_date'  => now()->toDateString(),
                'collection_time'  => '10:30 AM - 12:30 PM',
            ]);

        $submitRes->assertSessionHasNoErrors();

        // Verify order record created in database with null user_id (guest)
        $order = Order::where('customer_email', $guestEmail)->latest()->first();
        $this->assertNotNull($order);
        $this->assertNull($order->user_id);
        $this->assertEquals($guestName, $order->customer_name);
        $this->assertEquals($guestPhone, $order->customer_phone);
        $this->assertEquals('self_collection', $order->fulfillment_type);
        $this->assertEquals('W-001', $order->collection_token);
        $this->assertEquals(80.00, $order->total); // 2 * RM 40.00 walkin price

        // Follow redirect to Order Confirmation Page
        $submitRes->assertRedirect(route('checkout.success', $order));

        $confirmationRes = $this->withSession(['last_placed_order_id' => $order->id])
            ->get(route('checkout.success', $order));

        $confirmationRes->assertStatus(200);
        $confirmationRes->assertSee('W-001');
        $confirmationRes->assertSee('Wendy Test');
        $confirmationRes->assertSee('Tiger Prawns Grade A');
        $confirmationRes->assertSee('wendy.test@mstseafood.com');
        $confirmationRes->assertSee('Create an Account for Faster Future Orders');

        // Customer later registers an account with the same email
        $user = User::factory()->create([
            'name'           => 'Wendy Test Registered',
            'email'          => $guestEmail,
            'phone'          => $guestPhone,
            'customer_group' => 'retail',
        ]);

        // Trigger order linking (occurs automatically upon login / registration / dashboard visit)
        Order::linkGuestOrdersToUser($user);

        // Verify the order is now linked to Wendy's new user account
        $order->refresh();
        $this->assertEquals($user->id, $order->user_id);

        // Verify the linked order displays on Wendy's account orders page
        $accountOrdersRes = $this->actingAs($user)->get(route('account.orders'));
        $accountOrdersRes->assertStatus(200);
        $accountOrdersRes->assertSee($order->order_number);
        $accountOrdersRes->assertSee('80.00');
    }

    /**
     * Test Step 5: Guest Delivery Checkout redirects to Stripe Hosted Checkout URL
     */
    public function test_step_5_guest_delivery_checkout_redirects_to_stripe_hosted_checkout(): void
    {
        $user = User::create([
            'name'           => 'Wendy Retail',
            'email'          => 'wendy.retail@example.com',
            'password'       => bcrypt('password'),
            'customer_group' => 'retail',
            'phone'          => '0123456789',
        ]);

        Cart::create([
            'user_id'        => $user->id,
            'product_id'     => $this->productA->id,
            'quantity'       => 2,
            'customer_group' => 'retail',
        ]);

        \App\Models\Setting::set('stripe_test_secret', 'sk_test_51PyYjkDpoXnXuIQ8fgtlA26eW29YFXwrtG8cpzulvuPAwOm3tzIne68QML22U9DuacErbvw5J7t4YawCJwLnEHP200AZqQG9aF');
        \App\Models\Setting::configureStripe();

        $response = $this->actingAs($user)->post(route('checkout.store'), [
            'fulfillment_type' => 'delivery',
            'customer_name'    => 'Wendy Retail',
            'customer_phone'   => '0123456789',
            'customer_email'   => 'wendy.retail@example.com',
            'address'          => '7 Jalan SILC 2/18',
            'city'             => 'Iskandar Puteri',
            'state'            => 'Johor',
            'postcode'         => '79100',
            'group'            => 'retail',
            'payment_method'   => 'stripe',
        ]);

        $response->assertStatus(302);
        $redirectUrl = $response->headers->get('Location');
        $this->assertStringContainsString('checkout.stripe.com', $redirectUrl);
    }
}
