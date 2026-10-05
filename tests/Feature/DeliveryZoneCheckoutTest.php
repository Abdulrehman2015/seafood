<?php

namespace Tests\Feature;

use App\Models\DeliveryZone;
use App\Models\User;
use App\Services\DeliveryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeliveryZoneCheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected DeliveryService $deliveryService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->deliveryService = app(DeliveryService::class);

        // Ensure test zones exist
        DeliveryZone::firstOrCreate(
            ['code' => 'ZONE-A'],
            [
                'name' => 'Zone A - Johor Bahru & Iskandar Puteri',
                'description' => 'Selected Johor Bahru and Iskandar Puteri areas',
                'postcodes' => ['79000', '79100', '79200', '79250', '80000', '80100', '80200', '80300'],
                'areas' => ['Johor Bahru', 'Iskandar Puteri', 'Skudai', 'Medini', 'Puteri Harbour'],
                'states' => ['Johor'],
                'delivery_fee' => 0.00,
                'below_threshold_fee' => 10.00,
                'is_b2c_enabled' => true,
                'is_b2b_enabled' => true,
                'is_trading_enabled' => false,
                'is_active' => true,
                'manual_quotation_required' => false,
                'sort_order' => 1,
            ]
        );
    }

    /**
     * Test A: B2C RM50 Delivery
     * Expected: Checkout available, not blocked, zone matched, below-threshold fee applied (RM10), total RM60
     */
    public function test_a_b2c_rm50_delivery_calculates_fee(): void
    {
        $result = $this->deliveryService->calculateFee(
            subtotal: 50.00,
            fulfillmentType: 'delivery',
            state: 'Johor',
            city: 'Iskandar Puteri',
            postcode: '79100',
            group: 'retail'
        );

        $this->assertTrue($result['is_matched']);
        $this->assertFalse($result['requires_manual_arrangement']);
        $this->assertFalse($result['is_eligible_free_delivery']);
        $this->assertEquals('ZONE-A', $result['zone_code']);
        $this->assertEquals(10.00, $result['below_threshold_fee']);
        $this->assertEquals(10.00, $result['fee']);
        $this->assertEquals(150.00, $result['threshold']);
        $this->assertEquals(100.00, $result['shortfall_for_free_delivery']);
    }

    /**
     * Test B: B2C RM90 Delivery
     * Expected: Checkout works, no blocking, below-threshold fee applied
     */
    public function test_b_b2c_rm90_delivery_calculates_fee(): void
    {
        $result = $this->deliveryService->calculateFee(
            subtotal: 90.00,
            fulfillmentType: 'delivery',
            state: 'Johor',
            city: 'Iskandar Puteri',
            postcode: '79100',
            group: 'retail'
        );

        $this->assertTrue($result['is_matched']);
        $this->assertFalse($result['is_eligible_free_delivery']);
        $this->assertEquals(10.00, $result['below_threshold_fee']);
        $this->assertEquals(10.00, $result['fee']);
        $this->assertEquals(60.00, $result['shortfall_for_free_delivery']);
    }

    /**
     * Test C: B2C RM150 Delivery
     * Expected: Standard local delivery arrangement applies, no below-threshold fee (Free Delivery)
     */
    public function test_c_b2c_rm150_standard_delivery(): void
    {
        $result = $this->deliveryService->calculateFee(
            subtotal: 150.00,
            fulfillmentType: 'delivery',
            state: 'Johor',
            city: 'Iskandar Puteri',
            postcode: '79100',
            group: 'retail'
        );

        $this->assertTrue($result['is_matched']);
        $this->assertTrue($result['is_eligible_free_delivery']);
        $this->assertEquals(0.00, $result['below_threshold_fee']);
        $this->assertEquals(0.00, $result['fee']);
    }

    /**
     * Test D: B2B RM200 Delivery
     * Expected: Checkout works, no RM350 block, below-threshold fee calculated
     */
    public function test_d_b2b_rm200_delivery_calculates_fee(): void
    {
        $result = $this->deliveryService->calculateFee(
            subtotal: 200.00,
            fulfillmentType: 'delivery',
            state: 'Johor',
            city: 'Iskandar Puteri',
            postcode: '79100',
            group: 'wholesale'
        );

        $this->assertTrue($result['is_matched']);
        $this->assertFalse($result['requires_manual_arrangement']);
        $this->assertFalse($result['is_eligible_free_delivery']);
        $this->assertEquals(350.00, $result['threshold']);
        $this->assertEquals(10.00, $result['below_threshold_fee']);
        $this->assertEquals(10.00, $result['fee']);
        $this->assertEquals(150.00, $result['shortfall_for_free_delivery']);
    }

    /**
     * Test E: B2B RM350 Delivery
     * Expected: Standard local delivery arrangement applies
     */
    public function test_e_b2b_rm350_standard_delivery(): void
    {
        $result = $this->deliveryService->calculateFee(
            subtotal: 350.00,
            fulfillmentType: 'delivery',
            state: 'Johor',
            city: 'Iskandar Puteri',
            postcode: '79100',
            group: 'wholesale'
        );

        $this->assertTrue($result['is_matched']);
        $this->assertTrue($result['is_eligible_free_delivery']);
        $this->assertEquals(0.00, $result['below_threshold_fee']);
        $this->assertEquals(0.00, $result['fee']);
    }

    /**
     * Test F: Walk-In RM20
     * Expected: Checkout works, Delivery Fee = RM0, no threshold, no postcode needed
     */
    public function test_f_walkin_rm20_free_pickup(): void
    {
        $result = $this->deliveryService->calculateFee(
            subtotal: 20.00,
            fulfillmentType: 'self_collection',
            group: 'retail'
        );

        $this->assertTrue($result['is_self_collection']);
        $this->assertEquals(0.00, $result['fee']);
        $this->assertEquals(0.00, $result['base_delivery_fee']);
        $this->assertEquals(0.00, $result['below_threshold_fee']);
        $this->assertStringContainsString('Walk-in / Counter Collection', $result['message']);
    }

    /**
     * Test G: Outside Standard Delivery Area
     * Expected: Order not blocked, flags manual arrangement with WhatsApp direct link
     */
    public function test_g_outside_standard_delivery_area_flags_manual_arrangement(): void
    {
        $result = $this->deliveryService->calculateFee(
            subtotal: 50.00,
            fulfillmentType: 'delivery',
            state: 'Other',
            city: 'Remote Location',
            postcode: '99999',
            group: 'retail'
        );

        $this->assertTrue($result['requires_manual_arrangement']);
        $this->assertFalse($result['is_matched']);
        $this->assertStringContainsString('Outstation Cold-Chain Delivery', $result['message']);
    }

    /**
     * Test API endpoint live response
     */
    public function test_calculate_delivery_fee_api_endpoint(): void
    {
        $response = $this->postJson('/api/calculate-delivery-fee', [
            'fulfillment_type' => 'delivery',
            'postcode' => '79100',
            'city' => 'Iskandar Puteri',
            'state' => 'Johor',
            'subtotal' => 50.00,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'fee',
            'base_delivery_fee',
            'below_threshold_fee',
            'zone_code',
            'zone_name',
            'message',
            'total',
            'subtotal',
        ]);
        $response->assertJson([
            'fee' => 10.0,
            'below_threshold_fee' => 10.0,
            'zone_code' => 'ZONE-A',
            'total' => 60.00,
        ]);
    }
}
