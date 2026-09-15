<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DynamicStoreAndCustomerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $customer;
    protected Category $category;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email'          => 'admin@oceanfresh.com',
            'customer_group' => 'admin',
        ]);

        $this->customer = User::factory()->create([
            'email'           => 'customer@test.com',
            'name'            => 'John Doe',
            'customer_group'  => 'retail',
            'approval_status' => 'approved',
        ]);

        $this->category = Category::create([
            'name'        => 'Fresh Crabs',
            'slug'        => 'fresh-crabs',
            'is_active'   => true,
            'description' => 'Crabs category',
        ]);

        $this->product = Product::create([
            'name'                => 'Mud Crab XL',
            'slug'                => 'mud-crab-xl',
            'category_id'         => $this->category->id,
            'retail_price'        => 88.00,
            'walkin_price'        => 75.00,
            'wholesale_price'     => 65.00,
            'retail_moq'          => 1,
            'walkin_moq'          => 1,
            'wholesale_moq'       => 5,
            'stock_quantity'      => 50,
            'unit'                => 'kg',
            'is_active'           => true,
            'is_walkin_available' => true,
        ]);

        Setting::set('store_phone', '+60 19-999 8888');
        Setting::set('store_email', 'hq@oceanfresh.com');
    }

    public function test_contact_page_renders_dynamic_settings(): void
    {
        $response = $this->get(route('contact'));

        $response->assertStatus(200);
        $response->assertSee('+60 19-999 8888');
        $response->assertSee('hq@oceanfresh.com');
        $response->assertSee('Fresh Crabs'); // in footer
    }

    public function test_contact_form_submission_stores_message(): void
    {
        $response = $this->post(route('contact.submit'), [
            'name'    => 'Michael Wong',
            'email'   => 'michael@test.com',
            'phone'   => '0123344556',
            'subject' => 'Wholesale Account',
            'message' => 'We want to order 500kg of Mud Crabs weekly for our seafood restaurant.',
        ]);

        $response->assertSessionHas('success');

        $message = ContactMessage::where('email', 'michael@test.com')->first();
        $this->assertNotNull($message);
        $this->assertEquals('Michael Wong', $message->name);
        $this->assertEquals('Wholesale Account', $message->subject);
        $this->assertFalse($message->is_read);
    }

    public function test_admin_can_view_inquiries_and_mark_as_read(): void
    {
        $msg = ContactMessage::create([
            'name'       => 'Sarah Tan',
            'email'      => 'sarah@test.com',
            'subject'    => 'Cold Chain Delivery',
            'message'    => 'Do you deliver to Penang?',
            'is_read'    => false,
        ]);

        $this->actingAs($this->admin);

        $indexRes = $this->get(route('admin.messages.index'));
        $indexRes->assertStatus(200);
        $indexRes->assertSee('Sarah Tan');

        $showRes = $this->get(route('admin.messages.show', $msg));
        $showRes->assertStatus(200);
        $showRes->assertSee('Do you deliver to Penang?');

        $this->assertTrue($msg->fresh()->is_read);
    }

    public function test_admin_can_update_customer_group_and_details(): void
    {
        $this->actingAs($this->admin);

        $response = $this->patch(route('admin.customers.update', $this->customer), [
            'name'            => 'Johnathan Doe',
            'email'           => 'customer@test.com',
            'phone'           => '0129988776',
            'customer_group'  => 'wholesale',
            'company_name'    => 'Ocean Bistro Sdn Bhd',
            'company_reg_no'  => '202401009988',
            'business_type'   => 'Restaurant Chain',
            'address'         => '45 Food Street',
            'city'            => 'Petaling Jaya',
            'state'           => 'Selangor',
            'postcode'        => '47300',
            'approval_status' => 'approved',
        ]);

        $response->assertSessionHas('success');

        $this->customer->refresh();
        $this->assertEquals('Johnathan Doe', $this->customer->name);
        $this->assertEquals('wholesale', $this->customer->customer_group);
        $this->assertEquals('Ocean Bistro Sdn Bhd', $this->customer->company_name);
    }

    public function test_product_detail_page_renders_cleanly(): void
    {
        $response = $this->get(route('shop.show', $this->product));

        $response->assertStatus(200);
        $response->assertSee('Mud Crab XL');
        $response->assertSee('88.00');
        $response->assertSee('Fresh Crabs');
        $response->assertSee('Add to Cart');
    }

    public function test_cart_page_renders_with_items(): void
    {
        $this->actingAs($this->customer);

        $cartService = app(\App\Services\CartService::class);
        $cartService->add($this->product->id, 1);

        $response = $this->get(route('cart.index'));
        $response->assertStatus(200);
        $response->assertSee('Mud Crab XL');
        $response->assertSee('88.00');
    }
}
