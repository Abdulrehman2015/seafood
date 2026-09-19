@extends('layouts.admin')
@section('title', 'Admin Profile')

@section('content')
<style>
/* ─── Admin Profile Page Styles ───────────────────────────────────────── */
.profile-page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 14px;
    margin-bottom: 28px;
    padding-bottom: 20px;
    border-bottom: 1px solid #e2e8f0;
}
.profile-breadcrumb {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.78rem;
    color: #64748b;
    margin-bottom: 5px;
}
.profile-breadcrumb a {
    color: #2563eb;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.15s;
}
.profile-breadcrumb a:hover { color: #1d4ed8; text-decoration: underline; }
.profile-page-title {
    font-size: 1.35rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
    letter-spacing: -0.025em;
    display: flex;
    align-items: center;
    gap: 10px;
}
.profile-page-title-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: linear-gradient(135deg, #1d4ed8, #2563eb);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(37,99,235,0.3);
}
.profile-header-subtitle {
    font-size: 0.83rem;
    color: #64748b;
    margin-top: 3px;
    font-weight: 400;
}

/* ─── Alert Banners ────────────────────────────────────────────────────── */
.profile-alert {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 14px 18px;
    border-radius: 12px;
    margin-bottom: 22px;
    font-size: 0.88rem;
    font-weight: 500;
    line-height: 1.5;
}
.profile-alert-success {
    background: #f0fdf4;
    border: 1px solid #86efac;
    color: #166534;
}
.profile-alert-error {
    background: #fef2f2;
    border: 1px solid #fca5a5;
    color: #991b1b;
}
.profile-alert-icon {
    font-size: 1.1rem;
    flex-shrink: 0;
    margin-top: 1px;
}

/* ─── Two-Column Profile Layout ───────────────────────────────────────── */
.profile-layout {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 24px;
    align-items: start;
}

/* ─── Avatar Card (Left) ───────────────────────────────────────────────── */
.profile-avatar-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 28px 20px 22px;
    text-align: center;
    box-shadow: 0 1px 4px rgba(15, 23, 42, 0.04), 0 4px 16px rgba(15, 23, 42, 0.04);
    position: sticky;
    top: 24px;
}
.avatar-wrapper {
    position: relative;
    display: inline-block;
    margin-bottom: 16px;
}
.avatar-img {
    width: 108px;
    height: 108px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #2563eb;
    box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12), 0 4px 14px rgba(37, 99, 235, 0.18);
    display: block;
}
.avatar-upload-label {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #2563eb;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    border: 2.5px solid #ffffff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    transition: background 0.2s, transform 0.15s;
    font-size: 0.8rem;
}
.avatar-upload-label:hover {
    background: #1d4ed8;
    transform: scale(1.08);
}
.avatar-name {
    font-size: 1.05rem;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 3px;
    line-height: 1.3;
}
.avatar-email {
    font-size: 0.8rem;
    color: #64748b;
    margin-bottom: 14px;
    word-break: break-all;
}
.avatar-role-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: linear-gradient(135deg, #eff6ff, #dbeafe);
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
    font-size: 0.72rem;
    font-weight: 700;
    padding: 5px 14px;
    border-radius: 999px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
.avatar-meta {
    margin-top: 18px;
    padding-top: 16px;
    border-top: 1px solid #f1f5f9;
}
.avatar-meta-item {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    font-size: 0.76rem;
    color: #94a3b8;
    text-align: left;
    line-height: 1.5;
    margin-bottom: 10px;
}
.avatar-meta-item:last-child { margin-bottom: 0; }
.avatar-meta-icon {
    width: 22px;
    height: 22px;
    border-radius: 6px;
    background: #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 0.7rem;
}
.avatar-remove-section {
    margin-top: 14px;
    padding-top: 12px;
    border-top: 1px solid #f1f5f9;
}
.avatar-remove-label {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-size: 0.8rem;
    color: #ef4444;
    cursor: pointer;
    font-weight: 500;
    transition: color 0.15s;
}
.avatar-remove-label:hover { color: #dc2626; }

/* ─── Right Column Cards ───────────────────────────────────────────────── */
.profile-cards-col {
    display: flex;
    flex-direction: column;
    gap: 20px;
}
.profile-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 24px 26px;
    box-shadow: 0 1px 4px rgba(15, 23, 42, 0.04), 0 4px 16px rgba(15, 23, 42, 0.04);
    transition: box-shadow 0.2s;
}
.profile-card:focus-within {
    box-shadow: 0 1px 4px rgba(15, 23, 42, 0.04), 0 6px 20px rgba(37, 99, 235, 0.08);
    border-color: #c7d7fd;
}
.profile-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 16px;
    margin-bottom: 20px;
    border-bottom: 1px solid #f1f5f9;
}
.profile-card-heading-group {}
.profile-card-title {
    font-size: 0.98rem;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 2px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.profile-card-title-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #2563eb;
    flex-shrink: 0;
}
.profile-card-desc {
    font-size: 0.8rem;
    color: #64748b;
    margin: 0;
}
.profile-card-badge {
    font-size: 0.72rem;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 6px;
    background: #f1f5f9;
    color: #475569;
    letter-spacing: 0.02em;
}
.profile-card-badge-warning {
    background: #fffbeb;
    color: #92400e;
}

/* ─── Form Fields ──────────────────────────────────────────────────────── */
.profile-form-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
}
.profile-form-grid-3 {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 18px;
}
.field-group {
    display: flex;
    flex-direction: column;
}
.field-label {
    display: block;
    font-size: 0.82rem;
    font-weight: 600;
    color: #334155;
    margin-bottom: 6px;
    letter-spacing: 0.01em;
}
.field-label-required {
    color: #ef4444;
    margin-left: 2px;
}
.field-input {
    width: 100%;
    height: 42px;
    border-radius: 9px;
    border: 1.5px solid #cbd5e1;
    padding: 0 13px;
    font-size: 0.88rem;
    color: #0f172a;
    background: #ffffff;
    box-sizing: border-box;
    transition: border-color 0.2s, box-shadow 0.2s;
    font-family: inherit;
    outline: none;
}
.field-input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.13);
    background: #fdfdff;
}
.field-input.has-error {
    border-color: #f87171;
    box-shadow: 0 0 0 3px rgba(248, 113, 113, 0.12);
}
.field-error-msg {
    color: #ef4444;
    font-size: 0.76rem;
    margin-top: 5px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 4px;
}
.field-hint {
    font-size: 0.75rem;
    color: #94a3b8;
    margin-top: 4px;
}

/* Password Input Wrapper (with show/hide toggle) */
.password-input-wrapper {
    position: relative;
}
.password-input-wrapper .field-input {
    padding-right: 42px;
}
.password-toggle-btn {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    color: #94a3b8;
    padding: 2px;
    display: flex;
    align-items: center;
    transition: color 0.15s;
    line-height: 1;
}
.password-toggle-btn:hover { color: #2563eb; }

/* Strength Meter */
.password-strength-bar {
    height: 4px;
    border-radius: 4px;
    background: #e2e8f0;
    margin-top: 8px;
    overflow: hidden;
}
.password-strength-fill {
    height: 100%;
    border-radius: 4px;
    transition: width 0.3s ease, background 0.3s ease;
    width: 0%;
}

/* ─── Action Row ───────────────────────────────────────────────────────── */
.profile-actions-row {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 4px;
    padding-top: 6px;
}
.profile-btn-cancel {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 10px 20px;
    border-radius: 9px;
    border: 1.5px solid #e2e8f0;
    background: #ffffff;
    color: #475569;
    font-size: 0.88rem;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.18s;
    font-family: inherit;
}
.profile-btn-cancel:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #0f172a;
}
.profile-btn-save {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 24px;
    border-radius: 9px;
    border: none;
    background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
    color: #ffffff;
    font-size: 0.88rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.18s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: 0 2px 10px rgba(37, 99, 235, 0.28);
    font-family: inherit;
}
.profile-btn-save:hover {
    background: linear-gradient(135deg, #1e40af 0%, #1d4ed8 100%);
    box-shadow: 0 6px 18px rgba(37, 99, 235, 0.38);
    transform: translateY(-1px);
}
.profile-btn-save:active {
    transform: translateY(0);
    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.22);
}

/* ─── Responsive ───────────────────────────────────────────────────────── */
@media (max-width: 1024px) {
    .profile-layout {
        grid-template-columns: 240px 1fr;
        gap: 18px;
    }
    .profile-card {
        padding: 20px 22px;
    }
}

@media (max-width: 860px) {
    .profile-layout {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    .profile-avatar-card {
        position: static;
        /* Horizontal layout for avatar on tablet */
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 22px;
        text-align: left;
        padding: 22px 24px;
    }
    .avatar-wrapper { margin-bottom: 0; flex-shrink: 0; }
    .profile-avatar-right { flex: 1; min-width: 0; }
    .avatar-role-badge { }
    .avatar-meta {
        margin-top: 12px;
        padding-top: 10px;
    }
}

@media (max-width: 640px) {
    .profile-page-header {
        margin-bottom: 20px;
        padding-bottom: 16px;
    }
    .profile-page-title { font-size: 1.15rem; }
    .profile-avatar-card {
        flex-direction: column;
        text-align: center;
        gap: 14px;
        padding: 20px 16px;
        border-radius: 14px;
    }
    .avatar-meta-item { justify-content: center; text-align: center; }
    .avatar-img { width: 90px; height: 90px; }
    .profile-form-grid-2 {
        grid-template-columns: 1fr !important;
        gap: 14px;
    }
    .profile-card {
        padding: 18px 16px;
        border-radius: 14px;
    }
    .profile-actions-row {
        flex-direction: column-reverse;
        gap: 10px;
    }
    .profile-btn-cancel,
    .profile-btn-save {
        width: 100%;
        justify-content: center;
        padding: 12px 20px;
        font-size: 0.9rem;
    }
}

@media (max-width: 400px) {
    .profile-card {
        padding: 16px 14px;
    }
    .avatar-img { width: 80px; height: 80px; }
    .profile-page-title {
        font-size: 1.05rem;
        gap: 8px;
    }
    .profile-page-title-icon {
        width: 32px;
        height: 32px;
        font-size: 0.85rem;
    }
}
</style>

{{-- ─── Page Header ───────────────────────────────────────────────────── --}}
<div class="profile-page-header">
    <div>
        <div class="profile-breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <span>›</span>
            <span>Settings</span>
            <span>›</span>
            <span style="color:#0f172a;font-weight:600">Admin Profile</span>
        </div>
        <h1 class="profile-page-title">
            <span class="profile-page-title-icon">👤</span>
            My Profile &amp; Account
        </h1>
        <div class="profile-header-subtitle">Manage your personal information, email, and security settings</div>
    </div>
</div>

{{-- ─── Alerts ───────────────────────────────────────────────────────────── --}}
@if(session('success'))
    <div class="profile-alert profile-alert-success" role="alert">
        <span class="profile-alert-icon">✅</span>
        <div>{{ session('success') }}</div>
    </div>
@endif

@if($errors->any())
    <div class="profile-alert profile-alert-error" role="alert">
        <span class="profile-alert-icon">⚠️</span>
        <div>
            <strong>Please fix the following errors:</strong>
            <ul style="margin:6px 0 0;padding-left:18px">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

{{-- ─── Profile Form ──────────────────────────────────────────────────────── --}}
<form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" id="profileForm" autocomplete="off">
    @csrf
    @method('PUT')

    <div class="profile-layout">

        {{-- ── LEFT: Avatar Card ──────────────────────────────────────────── --}}
        <div class="profile-avatar-card">
            {{-- Avatar image + upload button --}}
            <div class="avatar-wrapper">
                <img id="avatarPreview"
                     src="{{ $user->avatar_url }}"
                     alt="{{ $user->name }}"
                     class="avatar-img">
                <label for="avatarInput" class="avatar-upload-label" title="Upload new photo">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                        <circle cx="12" cy="13" r="4"/>
                    </svg>
                </label>
                <input type="file" id="avatarInput" name="avatar" accept="image/png,image/jpeg,image/webp" style="display:none">
            </div>

            {{-- Avatar info text --}}
            <div class="profile-avatar-right">
                <div class="avatar-name">{{ $user->name }}</div>
                <div class="avatar-email">{{ $user->email }}</div>
                <span class="avatar-role-badge">🛡️ Administrator</span>

                {{-- Meta info --}}
                <div class="avatar-meta">
                    <div class="avatar-meta-item">
                        <span class="avatar-meta-icon">📁</span>
                        <span>PNG, JPG or WEBP format · Max 2 MB</span>
                    </div>
                    <div class="avatar-meta-item">
                        <span class="avatar-meta-icon">🔒</span>
                        <span>Profile is visible only to you and super-admins</span>
                    </div>
                </div>

                {{-- Remove avatar --}}
                @if($user->avatar)
                    <div class="avatar-remove-section">
                        <label class="avatar-remove-label" for="removeAvatarCheck">
                            <input type="checkbox" name="remove_avatar" value="1" id="removeAvatarCheck" style="cursor:pointer;accent-color:#ef4444">
                            <span>Remove current photo</span>
                        </label>
                    </div>
                @endif

                @error('avatar')
                    <div class="field-error-msg" style="margin-top:8px">
                        <span>⚠</span> {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        {{-- ── RIGHT: Cards Column ────────────────────────────────────────── --}}
        <div class="profile-cards-col">

            {{-- Card 1: Account Details --}}
            <div class="profile-card">
                <div class="profile-card-header">
                    <div class="profile-card-heading-group">
                        <div class="profile-card-title">
                            <span class="profile-card-title-dot"></span>
                            Account Details
                        </div>
                        <p class="profile-card-desc">Update your display name and administrative email address</p>
                    </div>
                    <span class="profile-card-badge">Personal Info</span>
                </div>

                <div class="profile-form-grid-2">
                    <div class="field-group">
                        <label class="field-label" for="nameInput">
                            Full Name <span class="field-label-required">*</span>
                        </label>
                        <input type="text"
                               id="nameInput"
                               name="name"
                               value="{{ old('name', $user->name) }}"
                               required
                               class="field-input {{ $errors->has('name') ? 'has-error' : '' }}"
                               placeholder="Your full name"
                               autocomplete="name">
                        @error('name')
                            <div class="field-error-msg"><span>⚠</span> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="emailInput">
                            Email Address <span class="field-label-required">*</span>
                        </label>
                        <input type="email"
                               id="emailInput"
                               name="email"
                               value="{{ old('email', $user->email) }}"
                               required
                               class="field-input {{ $errors->has('email') ? 'has-error' : '' }}"
                               placeholder="admin@company.com"
                               autocomplete="email">
                        @error('email')
                            <div class="field-error-msg"><span>⚠</span> {{ $message }}</div>
                        @enderror
                        <span class="field-hint">Used for admin login and system notifications</span>
                    </div>
                </div>
            </div>

            {{-- Card 2: Change Password --}}
            <div class="profile-card">
                <div class="profile-card-header">
                    <div class="profile-card-heading-group">
                        <div class="profile-card-title">
                            <span class="profile-card-title-dot" style="background:#f59e0b"></span>
                            Change Password
                        </div>
                        <p class="profile-card-desc">Leave all password fields blank if you don't wish to update it</p>
                    </div>
                    <span class="profile-card-badge profile-card-badge-warning">Security</span>
                </div>

                <div style="display:flex;flex-direction:column;gap:16px">
                    {{-- Current password --}}
                    <div class="field-group">
                        <label class="field-label" for="currentPasswordInput">Current Password</label>
                        <div class="password-input-wrapper">
                            <input type="password"
                                   id="currentPasswordInput"
                                   name="current_password"
                                   autocomplete="current-password"
                                   placeholder="Enter your existing password to verify"
                                   class="field-input {{ $errors->has('current_password') ? 'has-error' : '' }}">
                            <button type="button" class="password-toggle-btn" onclick="togglePassword('currentPasswordInput', this)" tabindex="-1" aria-label="Show/hide password">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                        @error('current_password')
                            <div class="field-error-msg"><span>⚠</span> {{ $message }}</div>
                        @enderror
                    </div>

                    {{-- New + Confirm --}}
                    <div class="profile-form-grid-2">
                        <div class="field-group">
                            <label class="field-label" for="newPasswordInput">New Password</label>
                            <div class="password-input-wrapper">
                                <input type="password"
                                       id="newPasswordInput"
                                       name="password"
                                       autocomplete="new-password"
                                       placeholder="Minimum 8 characters"
                                       class="field-input {{ $errors->has('password') ? 'has-error' : '' }}"
                                       oninput="checkPasswordStrength(this.value)">
                                <button type="button" class="password-toggle-btn" onclick="togglePassword('newPasswordInput', this)" tabindex="-1" aria-label="Show/hide password">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="password-strength-bar">
                                <div class="password-strength-fill" id="strengthFill"></div>
                            </div>
                            <span class="field-hint" id="strengthLabel"></span>
                            @error('password')
                                <div class="field-error-msg"><span>⚠</span> {{ $message }}</div>
                            @enderror
                        </div>

                        <div class="field-group">
                            <label class="field-label" for="confirmPasswordInput">Confirm New Password</label>
                            <div class="password-input-wrapper">
                                <input type="password"
                                       id="confirmPasswordInput"
                                       name="password_confirmation"
                                       autocomplete="new-password"
                                       placeholder="Repeat new password"
                                       class="field-input"
                                       oninput="checkPasswordMatch()">
                                <button type="button" class="password-toggle-btn" onclick="togglePassword('confirmPasswordInput', this)" tabindex="-1" aria-label="Show/hide password">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </button>
                            </div>
                            <span class="field-hint" id="matchLabel"></span>
                        </div>
                    </div>

                    {{-- Security tips --}}
                    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:12px 14px;display:flex;gap:10px;align-items:flex-start">
                        <span style="font-size:1rem;flex-shrink:0;margin-top:1px">💡</span>
                        <div style="font-size:0.76rem;color:#64748b;line-height:1.6">
                            Use a strong password with <strong>uppercase, lowercase, numbers,</strong> and <strong>special characters</strong>. Avoid reusing passwords from other sites.
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="profile-actions-row">
                <a href="{{ route('admin.dashboard') }}" class="profile-btn-cancel">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                    Cancel
                </a>
                <button type="submit" class="profile-btn-save" id="saveProfileBtn">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                    </svg>
                    Save Profile Changes
                </button>
            </div>

        </div>{{-- end profile-cards-col --}}
    </div>{{-- end profile-layout --}}
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const avatarInput   = document.getElementById('avatarInput');
    const avatarPreview = document.getElementById('avatarPreview');
    const removeCheck   = document.getElementById('removeAvatarCheck');
    const saveBtn       = document.getElementById('saveProfileBtn');

    // ── Avatar preview on file select
    if (avatarInput && avatarPreview) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            // Validate size (2 MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Image must be less than 2 MB.');
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(evt) {
                avatarPreview.src = evt.target.result;
                if (removeCheck) removeCheck.checked = false;
            };
            reader.readAsDataURL(file);
        });
    }

    // ── Remove avatar checkbox
    if (removeCheck) {
        removeCheck.addEventListener('change', function() {
            if (this.checked) {
                avatarPreview.src = 'https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=1d4ed8&color=ffffff&bold=true&size=128';
                if (avatarInput) avatarInput.value = '';
            }
        });
    }

    // ── Save button loading state
    const form = document.getElementById('profileForm');
    if (form && saveBtn) {
        form.addEventListener('submit', function() {
            saveBtn.disabled = true;
            saveBtn.innerHTML = `
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="animation:spin 0.8s linear infinite">
                    <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                </svg>
                Saving...
            `;
        });
    }
});

// ── Password visibility toggle
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    if (!input) return;
    const isPass = input.type === 'password';
    input.type = isPass ? 'text' : 'password';
    btn.innerHTML = isPass
        ? `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>`
        : `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`;
    btn.style.color = isPass ? '#2563eb' : '#94a3b8';
}

// ── Password strength checker
function checkPasswordStrength(password) {
    const fill  = document.getElementById('strengthFill');
    const label = document.getElementById('strengthLabel');
    if (!fill || !label) return;

    if (!password) {
        fill.style.width  = '0%';
        fill.style.background = '#e2e8f0';
        label.textContent = '';
        return;
    }

    let score = 0;
    if (password.length >= 8)  score++;
    if (password.length >= 12) score++;
    if (/[A-Z]/.test(password)) score++;
    if (/[0-9]/.test(password)) score++;
    if (/[^A-Za-z0-9]/.test(password)) score++;

    const levels = [
        { pct: '20%', color: '#ef4444', text: 'Very weak' },
        { pct: '40%', color: '#f97316', text: 'Weak'      },
        { pct: '60%', color: '#eab308', text: 'Fair'      },
        { pct: '80%', color: '#22c55e', text: 'Strong'    },
        { pct: '100%',color: '#16a34a', text: 'Very strong'},
    ];
    const level = levels[Math.min(score - 1, 4)] || levels[0];
    fill.style.width      = level.pct;
    fill.style.background = level.color;
    label.textContent     = level.text;
    label.style.color     = level.color;
}

// ── Password match checker
function checkPasswordMatch() {
    const pw1   = document.getElementById('newPasswordInput');
    const pw2   = document.getElementById('confirmPasswordInput');
    const label = document.getElementById('matchLabel');
    if (!pw1 || !pw2 || !label) return;

    if (!pw2.value) { label.textContent = ''; pw2.classList.remove('has-error'); return; }

    if (pw1.value === pw2.value) {
        label.textContent = '✓ Passwords match';
        label.style.color = '#22c55e';
        pw2.classList.remove('has-error');
    } else {
        label.textContent = '✗ Passwords do not match';
        label.style.color = '#ef4444';
        pw2.classList.add('has-error');
    }
}

// ── Spinner keyframe
const style = document.createElement('style');
style.textContent = '@keyframes spin { to { transform: rotate(360deg); } }';
document.head.appendChild(style);
</script>
@endpush

@endsection
