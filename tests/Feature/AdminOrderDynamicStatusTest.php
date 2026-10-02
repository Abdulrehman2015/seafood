<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminOrderDynamicStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_order_status_via_ajax_and_form()
    {
        $admin = User::factory()->create([
            'customer_group' => 'admin',
            'email' => 'admin@example.com',
        ]);

        $order = Order::create([
            'order_number'      => 'ORD-TEST12345',
            'customer_name'     => 'Test Customer',
            'customer_email'    => 'test@example.com',
            'customer_phone'    => '60123456789',
            'customer_group'    => 'retail',
            'fulfillment_type'  => 'delivery',
            'status'            => 'pending',
            'payment_status'    => 'unpaid',
            'payment_method'    => 'stripe',
            'subtotal'          => 100.00,
            'shipping_fee'      => 10.00,
            'total'             => 110.00,
        ]);

        // 1. AJAX status update to 'processing'
        $response = $this->actingAs($admin)
            ->patchJson(route('admin.orders.update', $order), [
                'status' => 'processing',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'status'  => 'processing',
            ]);

        $this->assertEquals('processing', $order->fresh()->status);

        // 2. Form status update to 'shipped' + payment 'paid'
        $formResponse = $this->actingAs($admin)
            ->patch(route('admin.orders.update', $order), [
                'status'         => 'shipped',
                'payment_status' => 'paid',
                'shipping_fee'   => 15.00,
            ]);

        $formResponse->assertRedirect();
        $this->assertEquals('shipped', $order->fresh()->status);
        $this->assertEquals('paid', $order->fresh()->payment_status);
        $this->assertEquals(115.00, (float) $order->fresh()->total);

        // 3. Customer success page reflects the dynamic status
        $successPage = $this->withSession(['guest_order_id' => $order->id])
            ->get(route('checkout.success', ['order' => $order->id]));

        $successPage->assertStatus(200);
        $successPage->assertSee('Out for Delivery');
    }

    public function test_admin_can_update_walkin_order_lifecycle()
    {
        $admin = User::factory()->create([
            'customer_group' => 'admin',
            'email' => 'admin@example.com',
        ]);

        $walkinOrder = Order::create([
            'order_number'      => 'ORD-WALKIN01',
            'collection_token'  => 'W-005',
            'customer_name'     => 'Walkin Buyer',
            'customer_email'    => 'buyer@example.com',
            'customer_phone'    => '60123456789',
            'customer_group'    => 'walkin',
            'fulfillment_type'  => 'self_collection',
            'status'            => 'pending',
            'payment_status'    => 'unpaid',
            'payment_method'    => 'cash',
            'subtotal'          => 45.00,
            'shipping_fee'      => 0.00,
            'total'             => 45.00,
        ]);

        // Update to ready (Ready for Collection)
        $response = $this->actingAs($admin)
            ->patchJson(route('admin.orders.update', $walkinOrder), [
                'status' => 'ready',
            ]);

        $response->assertStatus(200);
        $this->assertEquals('ready', $walkinOrder->fresh()->status);

        // Success / token page reflects ready for collection
        $successPage = $this->withSession(['guest_order_id' => $walkinOrder->id])
            ->get(route('checkout.success', ['order' => $walkinOrder->id]));

        $successPage->assertStatus(200);
        $successPage->assertSee('Ready at Counter 2');
    }
}
