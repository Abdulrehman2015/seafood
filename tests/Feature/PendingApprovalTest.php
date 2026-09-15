<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PendingApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_homepage_when_accessing_pending_approval(): void
    {
        $response = $this->get(route('approval.pending'));

        $response->assertRedirect(route('home'));
    }

    public function test_approved_user_is_redirected_to_homepage_when_accessing_pending_approval(): void
    {
        $approvedUser = User::create([
            'name'            => 'Approved User',
            'email'           => 'approved@example.com',
            'password'        => bcrypt('password123'),
            'customer_group'  => 'wholesale',
            'approval_status' => 'approved',
            'phone'           => '0123456789',
            'address'         => 'Test Address',
            'city'            => 'Johor Bahru',
            'state'           => 'Johor',
            'postcode'        => '80000',
        ]);

        $response = $this->actingAs($approvedUser)->get(route('approval.pending'));

        $response->assertRedirect(route('home'));
    }

    public function test_logged_in_pending_user_can_access_pending_approval_page(): void
    {
        $pendingUser = User::create([
            'name'            => 'Pending Wholesale Buyer',
            'email'           => 'pending@example.com',
            'password'        => bcrypt('password123'),
            'customer_group'  => 'wholesale',
            'approval_status' => 'pending',
            'company_name'    => 'Oceanic Holdings Sdn Bhd',
            'phone'           => '0123456789',
            'address'         => 'Test Address',
            'city'            => 'Johor Bahru',
            'state'           => 'Johor',
            'postcode'        => '80000',
        ]);

        $response = $this->actingAs($pendingUser)->get(route('approval.pending'));

        $response->assertOk();
        $response->assertSee('Account Pending Verification');
        $response->assertSee('Verification in Progress');
        $response->assertSee('Oceanic Holdings Sdn Bhd');
    }

    public function test_logged_in_pending_user_is_locked_out_of_other_pages(): void
    {
        $pendingUser = User::create([
            'name'            => 'Pending User',
            'email'           => 'pending2@example.com',
            'password'        => bcrypt('password123'),
            'customer_group'  => 'wholesale',
            'approval_status' => 'pending',
            'phone'           => '0123456789',
            'address'         => 'Test Address',
            'city'            => 'Johor Bahru',
            'state'           => 'Johor',
            'postcode'        => '80000',
        ]);

        // Attempt to access home
        $resHome = $this->actingAs($pendingUser)->get('/');
        $resHome->assertRedirect(route('approval.pending'));

        // Attempt to access shop
        $resShop = $this->actingAs($pendingUser)->get('/shop');
        $resShop->assertRedirect(route('approval.pending'));

        // Attempt to access cart
        $resCart = $this->actingAs($pendingUser)->get('/cart');
        $resCart->assertRedirect(route('approval.pending'));

        // Attempt to access about
        $resAbout = $this->actingAs($pendingUser)->get('/about');
        $resAbout->assertRedirect(route('approval.pending'));

        // Attempt to access dashboard
        $resDashboard = $this->actingAs($pendingUser)->get('/dashboard');
        $resDashboard->assertRedirect(route('approval.pending'));
    }

    public function test_pending_user_can_sign_out(): void
    {
        $pendingUser = User::create([
            'name'            => 'Pending User',
            'email'           => 'pending3@example.com',
            'password'        => bcrypt('password123'),
            'customer_group'  => 'wholesale',
            'approval_status' => 'pending',
            'phone'           => '0123456789',
            'address'         => 'Test Address',
            'city'            => 'Johor Bahru',
            'state'           => 'Johor',
            'postcode'        => '80000',
        ]);

        $response = $this->actingAs($pendingUser)->post(route('logout'));

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_pending_user_on_pending_page_refreshes_after_admin_approval_and_redirects_to_dashboard(): void
    {
        $user = User::create([
            'name'            => 'Wholesale Applicant',
            'email'           => 'applicant@example.com',
            'password'        => bcrypt('password123'),
            'customer_group'  => 'wholesale',
            'approval_status' => 'pending',
            'phone'           => '0123456789',
            'address'         => 'Test Address',
            'city'            => 'Johor Bahru',
            'state'           => 'Johor',
            'postcode'        => '80000',
        ]);

        // 1. User loads the pending-approval page while pending
        $resInitial = $this->actingAs($user)->get(route('approval.pending'));
        $resInitial->assertOk();
        $this->assertTrue(session('was_on_pending_approval'));

        // 2. Admin approves the user in database
        $user->update([
            'approval_status' => 'approved',
            'approved_at'     => now(),
        ]);

        // 3. User refreshes the pending-approval page
        $resRefresh = $this->actingAs($user->fresh())->get(route('approval.pending'));

        // 4. Assert user is automatically redirected to their Dashboard!
        $resRefresh->assertRedirect(route('account.dashboard'));
        $resRefresh->assertSessionHas('success');
    }

    public function test_live_check_status_api(): void
    {
        $user = User::create([
            'name'            => 'Wholesale Applicant',
            'email'           => 'applicant2@example.com',
            'password'        => bcrypt('password123'),
            'customer_group'  => 'wholesale',
            'approval_status' => 'pending',
            'phone'           => '0123456789',
            'address'         => 'Test Address',
            'city'            => 'Johor Bahru',
            'state'           => 'Johor',
            'postcode'        => '80000',
        ]);

        // When pending
        $resPending = $this->actingAs($user)->getJson(route('approval.check_status'));
        $resPending->assertOk();
        $resPending->assertJson([
            'logged_in' => true,
            'approved'  => false,
            'status'    => 'pending',
        ]);

        // When approved
        $user->update(['approval_status' => 'approved']);
        $resApproved = $this->actingAs($user->fresh())->getJson(route('approval.check_status'));
        $resApproved->assertOk();
        $resApproved->assertJson([
            'logged_in' => true,
            'approved'  => true,
            'status'    => 'approved',
            'redirect'  => route('account.dashboard'),
        ]);
    }
}
