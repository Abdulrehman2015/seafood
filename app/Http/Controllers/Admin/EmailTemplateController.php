<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AccountApproved;
use App\Mail\AccountRejected;
use App\Mail\AdminNewOrderNotification;
use App\Mail\AdminNewUserRegistered;
use App\Mail\OrderConfirmation;
use App\Mail\QuotationReady;
use App\Mail\TestSmtpMail;
use App\Mail\UserRegistered;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Quotation;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailTemplateController extends Controller
{
    /**
     * Get metadata of all registered email templates.
     */
    protected function getTemplates(): array
    {
        return [
            'user-registered-retail' => [
                'name'        => 'User Registration (Retail Customer)',
                'category'    => 'Customer Authentication',
                'icon'        => '👤',
                'recipient'   => 'Customer (Upon Signup)',
                'subject'     => 'Welcome to ' . config('app.name', 'MST Import & Export') . '!',
                'description' => 'Dispatched automatically when a retail shopper registers an active account.',
                'view'        => 'emails.user-registered',
            ],
            'user-registered-wholesale' => [
                'name'        => 'User Registration (Wholesale / Pending)',
                'category'    => 'Customer Authentication',
                'icon'        => '⏳',
                'recipient'   => 'Wholesale/Trading Customer',
                'subject'     => 'Account Application Received — ' . config('app.name', 'MST Import & Export'),
                'description' => 'Sent to commercial partners confirming their B2B application is under review.',
                'view'        => 'emails.user-registered',
            ],
            'admin-new-user' => [
                'name'        => 'Admin New User Registration Alert',
                'category'    => 'Admin Notifications',
                'icon'        => '🚨',
                'recipient'   => 'System Administrator',
                'subject'     => 'New Wholesale Registration: John Smith — ' . config('app.name', 'MST Import & Export'),
                'description' => 'Alerts admins when a new user registers with business credentials and approval links.',
                'view'        => 'emails.admin-new-user',
            ],
            'account-approved' => [
                'name'        => 'Account Approved Notification',
                'category'    => 'Customer Status',
                'icon'        => '🎉',
                'recipient'   => 'Approved Customer',
                'subject'     => 'Your Account Has Been Approved — ' . config('app.name', 'MST Import & Export'),
                'description' => 'Notifies commercial customer when their account tier and wholesale pricing are activated.',
                'view'        => 'emails.account-approved',
            ],
            'account-rejected' => [
                'name'        => 'Account Application Update',
                'category'    => 'Customer Status',
                'icon'        => '⚠️',
                'recipient'   => 'Customer',
                'subject'     => 'Update on Your Account Application — ' . config('app.name', 'MST Import & Export'),
                'description' => 'Sent when an application requires additional business documents or is declined.',
                'view'        => 'emails.account-rejected',
            ],
            'test-smtp' => [
                'name'        => 'SMTP Connection Test',
                'category'    => 'System & Diagnostics',
                'icon'        => '⚡',
                'recipient'   => 'Admin / Target Email',
                'subject'     => 'SMTP Connection Test Confirmation — ' . config('app.name', 'MST Import & Export'),
                'description' => 'Diagnostic test email verifying outbound SMTP gateway connectivity and server specs.',
                'view'        => 'emails.test-smtp',
            ],
            'quotation-ready' => [
                'name'        => 'Formal Quotation Ready',
                'category'    => 'Commercial & Trading',
                'icon'        => '📑',
                'recipient'   => 'Trading Customer',
                'subject'     => 'Quotation Ready: #QT-2026-0089 — ' . config('app.name', 'MST Import & Export'),
                'description' => 'Dispatched when commercial team finishes preparing an RFQ custom quote.',
                'view'        => 'emails.quotation-ready',
            ],
            'order-confirmation' => [
                'name'        => 'Order Confirmation (Customer Receipt)',
                'category'    => 'Orders & Fulfillment',
                'icon'        => '📦',
                'recipient'   => 'Customer',
                'subject'     => 'Order Confirmed: #ORD-2026-9812 — ' . config('app.name', 'MST Import & Export'),
                'description' => 'Itemized receipt sent to the buyer after successful checkout and payment.',
                'view'        => 'emails.order-confirmation',
            ],
            'admin-new-order' => [
                'name'        => 'Admin New Order Alert',
                'category'    => 'Admin Notifications',
                'icon'        => '🔔',
                'recipient'   => 'System Administrator',
                'subject'     => 'New Order Received: #ORD-2026-9812 (RM 1,280.00) — ' . config('app.name', 'MST Import & Export'),
                'description' => 'Immediate notification for warehouse and distribution teams upon new order placement.',
                'view'        => 'emails.admin-new-order',
            ],
        ];
    }

    /**
     * Create mock data objects for realistic rendering.
     */
    protected function getMockData(string $templateKey): array
    {
        $mockRetailUser = new User([
            'name'            => 'Sarah Tan (陈小姐)',
            'email'           => 'sarahtan@example.com',
            'phone'           => '012-345 6789',
            'customer_group'  => 'retail',
            'approval_status' => 'approved',
            'address'         => '18, Jalan Molek 1/10, Taman Molek',
            'city'            => 'Johor Bahru',
            'state'           => 'Johor',
            'postcode'        => '81100',
            'created_at'      => now(),
        ]);
        $mockRetailUser->id = 101;

        $mockWholesaleUser = new User([
            'name'            => 'Alex Wong (王经理)',
            'email'           => 'alex.wong@grandseafoodrestaurant.com',
            'phone'           => '019-876 5432',
            'customer_group'  => 'wholesale',
            'approval_status' => 'pending',
            'company_name'    => 'Grand Ocean Seafood Restaurant Sdn. Bhd.',
            'company_reg_no'  => '202301048892 (1542890-X)',
            'business_type'   => 'Restaurant / F&B Chain',
            'address'         => '88, Jalan Sutera Tanjung 8/4, Taman Sutera Utama',
            'city'            => 'Skudai',
            'state'           => 'Johor',
            'postcode'        => '81300',
            'created_at'      => now(),
        ]);
        $mockWholesaleUser->id = 102;

        $mockApprovedWholesaleUser = clone $mockWholesaleUser;
        $mockApprovedWholesaleUser->id = 103;
        $mockApprovedWholesaleUser->approval_status = 'approved';

        $mockOrder = new Order([
            'order_number'     => 'ORD-2026-9812',
            'customer_name'    => 'Alex Wong (Grand Ocean)',
            'customer_email'   => 'alex.wong@grandseafoodrestaurant.com',
            'customer_phone'   => '019-876 5432',
            'customer_group'   => 'wholesale',
            'status'           => 'confirmed',
            'payment_status'   => 'paid',
            'payment_method'   => 'Stripe FPX Online',
            'fulfillment_type' => 'delivery',
            'shipping_address' => [
                'address'  => '88, Jalan Sutera Tanjung 8/4, Taman Sutera Utama',
                'city'     => 'Skudai',
                'state'    => 'Johor',
                'postcode' => '81300',
            ],
            'subtotal'         => 1280.00,
            'total'            => 1280.00,
            'created_at'       => now(),
        ]);
        $mockOrder->id = 9812;

        $item1 = new OrderItem([
            'product_name' => 'Premium Norwegian Atlantic Salmon Fillet (IQF)',
            'product_sku'  => 'SEA-SAL-001',
            'quantity'     => 10,
            'unit_price'   => 48.00,
            'subtotal'     => 480.00,
        ]);
        $item2 = new OrderItem([
            'product_name' => 'Wild-Caught Sea Tiger Prawns (Grade AAA, 1kg pack)',
            'product_sku'  => 'SEA-PRW-004',
            'quantity'     => 10,
            'unit_price'   => 80.00,
            'subtotal'     => 800.00,
        ]);
        $mockOrder->setRelation('items', collect([$item1, $item2]));

        $mockQuotation = new Quotation([
            'quotation_number' => 'QT-2026-0089',
            'status'           => 'ready',
            'total_amount'     => 5400.00,
            'valid_until'      => now()->addDays(14),
            'created_at'       => now(),
        ]);
        $mockQuotation->id = 89;
        $mockQuotation->setRelation('user', $mockApprovedWholesaleUser);

        return match ($templateKey) {
            'user-registered-retail'    => ['user' => $mockRetailUser],
            'user-registered-wholesale' => ['user' => $mockWholesaleUser],
            'admin-new-user'            => ['user' => $mockWholesaleUser],
            'account-approved'          => ['user' => $mockApprovedWholesaleUser],
            'account-rejected'          => ['user' => $mockWholesaleUser],
            'test-smtp'                 => [
                'targetEmail' => 'admin@mstseafood.com',
                'mailHost'    => Setting::get('mail_host', config('mail.mailers.smtp.host', 'smtp.gmail.com')),
                'fromAddress' => Setting::get('mail_from_address', config('mail.from.address', 'no-reply@mst.my')),
                'fromName'    => Setting::get('mail_from_name', config('mail.from.name', 'MST Import & Export')),
                'mailMailer'  => Setting::get('mail_mailer', config('mail.default', 'smtp')),
            ],
            'quotation-ready'           => ['quotation' => $mockQuotation],
            'order-confirmation'        => ['order' => $mockOrder],
            'admin-new-order'           => ['order' => $mockOrder],
            default                     => ['user' => $mockRetailUser],
        };
    }

    /**
     * List all email templates.
     */
    public function index()
    {
        $templates = $this->getTemplates();
        return view('admin.emails.index', compact('templates'));
    }

    /**
     * Preview a specific template rendered as HTML.
     */
    public function preview(string $template)
    {
        $templates = $this->getTemplates();
        if (!isset($templates[$template])) {
            abort(404, 'Email template not found.');
        }

        $meta = $templates[$template];
        $data = $this->getMockData($template);

        return view($meta['view'], $data);
    }

    /**
     * Send a real test email of a template to a specified address.
     */
    public function sendTest(Request $request, string $template)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        Setting::configureMailer();

        $templates = $this->getTemplates();
        if (!isset($templates[$template])) {
            return back()->with('error', 'Invalid email template.');
        }

        // ── SMTP Guard ─────────────────────────────────────────────────────────
        // Detect non-delivery mailers (log / array) which silently swallow mail.
        // The user MUST configure real SMTP before a live test makes sense.
        $activeMailer = Setting::get('mail_mailer', config('mail.default', 'log'));
        if (in_array(strtolower($activeMailer), ['log', 'array', 'null'])) {
            return back()->with('error',
                "⚠️ No SMTP configured — mail driver is set to \"{$activeMailer}\" which does not deliver real emails. " .
                "Please configure your SMTP settings in Store Settings → Email / SMTP tab first."
            );
        }

        $targetEmail = $request->email;
        $data = $this->getMockData($template);

        try {
            $mailable = match ($template) {
                'user-registered-retail', 'user-registered-wholesale' => new UserRegistered($data['user']),
                'admin-new-user'     => new AdminNewUserRegistered($data['user']),
                'account-approved'   => new AccountApproved($data['user']),
                'account-rejected'   => new AccountRejected($data['user']),
                'test-smtp'          => new TestSmtpMail($targetEmail, $data['mailHost'], $data['fromAddress'], $data['fromName']),
                'quotation-ready'    => new QuotationReady($data['quotation']),
                'order-confirmation' => new OrderConfirmation($data['order']),
                'admin-new-order'    => new AdminNewOrderNotification($data['order']),
                default              => new UserRegistered($data['user']),
            };

            Mail::to($targetEmail)->send($mailable);

            return back()->with('success', "✅ Test email for '{$templates[$template]['name']}' successfully sent to {$targetEmail}!");
        } catch (\Throwable $e) {
            return back()->with('error', "Failed to send test email: " . $e->getMessage());
        }
    }
}
