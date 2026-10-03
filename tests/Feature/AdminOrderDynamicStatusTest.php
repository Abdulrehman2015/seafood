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

    public function test_admin_can_notify_user_on_collection_date()
    {
        \Illuminate\Support\Facades\Mail::fake();

        $admin = User::factory()->create([
            'customer_group' => 'admin',
            'email' => 'admin@example.com',
        ]);

        $walkinOrder = Order::create([
            'order_number'      => 'ORD-WALKIN-NOTIFY',
            'collection_token'  => 'W-009',
            'customer_name'     => 'Ahmad Test',
            'customer_email'    => 'ahmad@example.com',
            'customer_phone'    => '60123456789',
            'customer_group'    => 'walkin',
            'fulfillment_type'  => 'self_collection',
            'collection_date'   => '2026-10-03',
            'collection_time'   => '08:30 AM - 10:30 AM',
            'status'            => 'pending',
            'payment_status'    => 'paid',
            'subtotal'          => 50.00,
            'total'             => 50.00,
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.orders.notify_schedule', $walkinOrder), [
                'confirmed_date'     => '2026-10-05',
                'confirmed_time'     => '10:30 AM - 12:30 PM',
                'notification_notes' => 'Packed and ready in cold locker #3.',
                'target_status'      => 'ready',
                'send_email'         => '1',
            ]);

        $response->assertRedirect();
        $fresh = $walkinOrder->fresh();
        $this->assertEquals('2026-10-05', $fresh->confirmed_date);
        $this->assertEquals('10:30 AM - 12:30 PM', $fresh->confirmed_time);
        $this->assertEquals('ready', $fresh->status);
        $this->assertNotNull($fresh->notified_at);
        $this->assertEquals('Packed and ready in cold locker #3.', $fresh->notification_notes);

        \Illuminate\Support\Facades\Mail::assertSent(\App\Mail\OrderScheduleNotification::class, function ($mail) use ($fresh) {
            return $mail->hasTo('ahmad@example.com') && $mail->order->id === $fresh->id;
        });

        // Test live tracker shows MST Confirmed date
        $successPage = $this->withSession(['guest_order_id' => $walkinOrder->id])
            ->get(route('checkout.success', ['order' => $walkinOrder->id]));
        $successPage->assertStatus(200);
        $successPage->assertSee('2026-10-05');
        $successPage->assertSee('10:30 AM - 12:30 PM');
    }

    public function test_admin_can_notify_user_on_delivery_date()
    {
        \Illuminate\Support\Facades\Mail::fake();

        $admin = User::factory()->create([
            'customer_group' => 'admin',
            'email' => 'admin@example.com',
        ]);

        $deliveryOrder = Order::create([
            'order_number'      => 'ORD-DELIV-NOTIFY',
            'customer_name'     => 'Delivery Customer',
            'customer_email'    => 'deliv@example.com',
            'customer_phone'    => '60129998888',
            'customer_group'    => 'retail',
            'fulfillment_type'  => 'delivery',
            'delivery_date'     => '2026-10-04',
            'shipping_address'  => ['address' => '123 Ocean Street', 'city' => 'Johor Bahru', 'state' => 'Johor', 'postcode' => '80000'],
            'status'            => 'processing',
            'payment_status'    => 'paid',
            'subtotal'          => 120.00,
            'total'             => 120.00,
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.orders.notify_schedule', $deliveryOrder), [
                'confirmed_date'     => '2026-10-06',
                'notification_notes' => 'Cold truck driver #4 will arrive around 11:00 AM.',
                'target_status'      => 'confirmed',
                'send_email'         => '1',
            ]);

        $response->assertRedirect();
        $fresh = $deliveryOrder->fresh();
        $this->assertEquals('2026-10-06', $fresh->confirmed_date);
        $this->assertEquals('confirmed', $fresh->status);
        $this->assertNotNull($fresh->notified_at);

        \Illuminate\Support\Facades\Mail::assertSent(\App\Mail\OrderScheduleNotification::class, function ($mail) use ($fresh) {
            return $mail->hasTo('deliv@example.com') && $mail->order->id === $fresh->id;
        });

        // Test live tracker shows MST Confirmed delivery date
        $successPage = $this->withSession(['guest_order_id' => $deliveryOrder->id])
            ->get(route('checkout.success', ['order' => $deliveryOrder->id]));
        $successPage->assertStatus(200);
        $successPage->assertSee('2026-10-06');
    }
}
