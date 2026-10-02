<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestCheckoutAndOrderLinkingTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_access_checkout_page_without_authentication(): void
    {
        $category = \App\Models\Category::create([
            'name'      => 'Prawns',
            'slug'      => 'prawns',
            'is_active' => true,
        ]);

        $product = Product::create([
            'name'           => 'Tiger Prawns (1kg)',
            'slug'           => 'tiger-prawns-1kg',
            'sku'            => 'TP-01',
            'category_id'    => $category->id,
            'retail_price'   => 45.00,
            'is_active'      => true,
            'stock_quantity' => 20,
        ]);

        // Mock CartService to return guest items
        $cartItem = new \App\Models\Cart([
            'product_id'     => $product->id,
            'quantity'       => 2,
            'customer_group' => 'retail',
        ]);
        $cartItem->setRelation('product', $product);

        $cartMock = $this->mock(\App\Services\CartService::class);
        $cartMock->shouldReceive('getItems')->andReturn(collect([$cartItem]));
        $cartMock->shouldReceive('totals')->andReturn([
            'subtotal' => 90.00,
            'total'    => 90.00,
        ]);

        $checkoutResponse = $this->get(route('checkout.index'));

        $checkoutResponse->assertStatus(200);
        $checkoutResponse->assertSee('name="customer_name"', false);
        $checkoutResponse->assertSee('name="customer_phone"', false);
        $checkoutResponse->assertSee('name="customer_email"', false);
        $checkoutResponse->assertSee('name="address"', false);
        $checkoutResponse->assertSee('Payment via Stripe');
    }

    public function test_past_guest_orders_automatically_link_to_new_user_account(): void
    {
        // 1. Create 2 guest orders under john@example.com / 0123456789
        $guestOrder1 = Order::create([
            'order_number'     => 'ORD-GUEST-001',
            'user_id'          => null,
            'customer_group'   => 'retail',
            'customer_name'    => 'John Tan',
            'customer_email'   => 'john@example.com',
            'customer_phone'   => '0123456789',
            'status'           => 'confirmed',
            'payment_status'   => 'paid',
            'payment_method'   => 'stripe',
            'fulfillment_type' => 'delivery',
            'subtotal'         => 120.00,
            'shipping_fee'     => 0.00,
            'total'            => 120.00,
        ]);

        $guestOrder2 = Order::create([
            'order_number'     => 'ORD-GUEST-002',
            'user_id'          => null,
            'customer_group'   => 'retail',
            'customer_name'    => 'John Tan',
            'customer_email'   => 'john@example.com',
            'customer_phone'   => '0123456789',
            'status'           => 'delivered',
            'payment_status'   => 'paid',
            'payment_method'   => 'stripe',
            'fulfillment_type' => 'delivery',
            'subtotal'         => 80.00,
            'shipping_fee'     => 10.00,
            'total'            => 90.00,
        ]);

        $this->assertNull($guestOrder1->user_id);
        $this->assertNull($guestOrder2->user_id);

        // 2. User registers later with the same email
        $user = User::factory()->create([
            'name'           => 'John Tan',
            'email'          => 'john@example.com',
            'phone'          => '0123456789',
            'customer_group' => 'retail',
        ]);

        // Link orders via model method or account view
        $linked = Order::linkGuestOrdersToUser($user);

        $this->assertEquals(2, $linked);

        $guestOrder1->refresh();
        $guestOrder2->refresh();

        $this->assertEquals($user->id, $guestOrder1->user_id);
        $this->assertEquals($user->id, $guestOrder2->user_id);

        // 3. User views orders in account dashboard
        $response = $this->actingAs($user)->get(route('account.orders'));
        $response->assertStatus(200);
        $response->assertSee('ORD-GUEST-001');
        $response->assertSee('ORD-GUEST-002');
    }
}
