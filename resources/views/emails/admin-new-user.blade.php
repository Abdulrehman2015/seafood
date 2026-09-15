@extends('emails.layout')

@section('title', 'New Customer Registration: ' . $user->name)
@section('preheader', 'New customer registration on MST Import & Export (' . ucfirst($user->customer_group) . ')')

@section('content')
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <!-- Badge -->
    <tr>
        <td>
            @if($user->isPending())
                <div style="display: inline-block; background-color: #fee2e2; color: #dc2626; font-size: 12px; font-weight: 800; padding: 4px 12px; border-radius: 9999px; border: 1px solid #fca5a5; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 12px;">
                    🚨 Action Required: Pending Approval
                </div>
            @else
                <div style="display: inline-block; background-color: #eff6ff; color: #1d4ed8; font-size: 12px; font-weight: 800; padding: 4px 12px; border-radius: 9999px; border: 1px solid #bfdbfe; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 12px;">
                    🔔 New Retail Customer Registered
                </div>
            @endif
        </td>
    </tr>

    <!-- Title -->
    <tr>
        <td>
            <h1 style="font-size: 20px; font-weight: 800; color: #0f172a; line-height: 1.3; margin: 0 0 8px 0;">
                New {{ ucfirst($user->customer_group) }} Registration
            </h1>
            <p style="font-size: 14px; color: #475569; line-height: 1.6; margin: 0 0 20px 0;">
                A new user has registered on <strong>MST Import &amp; Export SDN. BHD.</strong> website. Please review the details below:
            </p>
        </td>
    </tr>

    <!-- Customer Details Card -->
    <tr>
        <td style="padding-bottom: 24px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden;">
                <tr>
                    <td style="background-color: #f1f5f9; padding: 10px 16px; font-size: 12px; font-weight: 800; color: #475569; letter-spacing: 0.05em; text-transform: uppercase; border-bottom: 1px solid #e2e8f0;">
                        📋 Customer Profile Information
                    </td>
                </tr>
                <tr>
                    <td style="padding: 16px;">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 13px; line-height: 1.8;">
                            <tr>
                                <td width="35%" style="color: #64748b; font-weight: 600;">Customer Name:</td>
                                <td style="color: #0f172a; font-weight: 700;">{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">Email Address:</td>
                                <td style="color: #0f172a;"><a href="mailto:{{ $user->email }}" style="color: #2563eb; text-decoration: none;">{{ $user->email }}</a></td>
                            </tr>
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">Phone:</td>
                                <td style="color: #0f172a;">{{ $user->phone ?? 'Not provided' }}</td>
                            </tr>
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">Customer Group:</td>
                                <td>
                                    <span style="display: inline-block; background-color: {{ $user->customer_group === 'trading' ? '#f5f3ff; color: #7c3aed;' : ($user->customer_group === 'wholesale' ? '#eff6ff; color: #2563eb;' : '#ecfdf5; color: #059669;') }}; font-weight: 800; font-size: 11px; padding: 2px 8px; border-radius: 4px; text-transform: uppercase;">
                                        {{ $user->customer_group }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">Approval Status:</td>
                                <td>
                                    <strong style="color: {{ $user->isPending() ? '#d97706' : '#16a34a' }};">
                                        {{ ucfirst($user->approval_status ?? 'Approved') }}
                                    </strong>
                                </td>
                            </tr>
                            @if($user->company_name)
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">Company Name:</td>
                                <td style="color: #0f172a; font-weight: 700;">{{ $user->company_name }}</td>
                            </tr>
                            @endif
                            @if($user->company_reg_no)
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">SSM Reg No:</td>
                                <td style="color: #0f172a;">{{ $user->company_reg_no }}</td>
                            </tr>
                            @endif
                            @if($user->business_type)
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">Business Type:</td>
                                <td style="color: #0f172a;">{{ $user->business_type }}</td>
                            </tr>
                            @endif
                            @if($user->address)
                            <tr>
                                <td style="color: #64748b; font-weight: 600; vertical-align: top;">Location:</td>
                                <td style="color: #0f172a;">{{ $user->address }}, {{ $user->city }}, {{ $user->state }} {{ $user->postcode }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">Timestamp:</td>
                                <td style="color: #0f172a;">{{ $user->created_at ? $user->created_at->format('d M Y, h:i:s A') : date('d M Y, h:i:s A') }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- Admin Action Buttons -->
    <tr>
        <td align="center" style="padding-bottom: 20px;">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="center" style="background-color: #2563eb; border-radius: 8px; margin-right: 8px;">
                        @if($user->id)
                        <a href="{{ route('admin.customers.show', $user->id) }}" target="_blank" style="display: inline-block; padding: 12px 28px; font-size: 14px; font-weight: 700; color: #ffffff; text-decoration: none; border-radius: 8px;">
                            View Customer Profile ➔
                        </a>
                        @else
                        <a href="{{ route('admin.customers.index') }}" target="_blank" style="display: inline-block; padding: 12px 28px; font-size: 14px; font-weight: 700; color: #ffffff; text-decoration: none; border-radius: 8px;">
                            View All Customers ➔
                        </a>
                        @endif
                    </td>
                    @if($user->isPending())
                    <td width="12"></td>
                    <td align="center" style="background-color: #16a34a; border-radius: 8px;">
                        <a href="{{ route('admin.customers.index', ['status' => 'pending']) }}" target="_blank" style="display: inline-block; padding: 12px 28px; font-size: 14px; font-weight: 700; color: #ffffff; text-decoration: none; border-radius: 8px;">
                            Approve / Reject ➔
                        </a>
                    </td>
                    @endif
                </tr>
            </table>
        </td>
    </tr>
</table>
@endsection
