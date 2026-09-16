@extends('layouts.admin')
@section('title', 'Admin Profile')

@section('content')
<div class="admin-topbar" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;margin-bottom:24px;padding-bottom:18px;border-bottom:1px solid #e2e8f0">
    <div>
        <div style="display:flex;align-items:center;gap:6px;font-size:0.82rem;color:#64748b;margin-bottom:4px">
            <a href="{{ route('admin.dashboard') }}" style="color:#2563eb;text-decoration:none">Dashboard</a>
            <span>›</span>
            <span>Settings</span>
            <span>›</span>
            <span style="color:#0f172a;font-weight:600">Admin Profile</span>
        </div>
        <h1 class="admin-page-title" style="margin:0;display:flex;align-items:center;gap:10px">
            <span>👤</span> My Profile &amp; Account
        </h1>
    </div>
</div>

<form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" id="profileForm">
    @csrf
    @method('PUT')

    <div style="display:grid;grid-template-columns:300px 1fr;gap:24px;align-items:start" class="admin-profile-grid">
        
        <!-- Left Column: Avatar & Summary -->
        <div class="card" style="padding:26px 20px;text-align:center;border-radius:14px;border:1px solid #e2e8f0;background:#ffffff;box-shadow:0 1px 3px rgba(0,0,0,0.03)">
            <div style="position:relative;display:inline-block;margin-bottom:16px">
                <img id="avatarPreview"
                     src="{{ $user->avatar_url }}"
                     alt="{{ $user->name }}"
                     style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:3.5px solid #2563eb;box-shadow:0 4px 12px rgba(37,99,235,0.2)">
                
                <label for="avatarInput"
                       style="position:absolute;bottom:0;right:0;width:36px;height:36px;border-radius:50%;background:#2563eb;color:#ffffff;display:flex;align-items:center;justify-content:center;cursor:pointer;border:2.5px solid #ffffff;box-shadow:0 2px 6px rgba(0,0,0,0.15);transition:transform 0.15s ease"
                       title="Upload new picture">
                    📷
                </label>
                <input type="file" id="avatarInput" name="avatar" accept="image/png,image/jpeg,image/webp" style="display:none">
            </div>

            <h3 style="margin:0 0 4px;font-size:1.15rem;font-weight:700;color:#0f172a">{{ $user->name }}</h3>
            <div style="font-size:0.82rem;color:#64748b;margin-bottom:12px">{{ $user->email }}</div>

            <span style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;font-size:0.75rem;font-weight:700;padding:4px 12px;border-radius:999px;display:inline-block;text-transform:uppercase;letter-spacing:0.04em">
                🛡️ Administrator
            </span>

            <div style="margin-top:20px;padding-top:16px;border-top:1px solid #f1f5f9;font-size:0.76rem;color:#94a3b8;line-height:1.4">
                Supported formats: PNG, JPG, WEBP<br>Maximum file size: 2MB
            </div>

            @if($user->avatar)
                <div style="margin-top:14px;padding-top:12px;border-top:1px solid #f1f5f9">
                    <label style="display:inline-flex;align-items:center;gap:6px;font-size:0.82rem;color:#ef4444;cursor:pointer">
                        <input type="checkbox" name="remove_avatar" value="1" id="removeAvatarCheck" style="cursor:pointer">
                        <span>Remove current photo</span>
                    </label>
                </div>
            @endif

            @error('avatar')
                <div style="color:#ef4444;font-size:0.8rem;margin-top:8px;font-weight:600">{{ $message }}</div>
            @enderror
        </div>

        <!-- Right Column: Personal Information & Password -->
        <div style="display:flex;flex-direction:column;gap:20px">
            
            <!-- Card 1: Account Information -->
            <div class="card" style="padding:24px 26px;border-radius:14px;border:1px solid #e2e8f0;background:#ffffff;box-shadow:0 1px 3px rgba(0,0,0,0.03)">
                <div style="border-bottom:1px solid #f1f5f9;padding-bottom:14px;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between">
                    <div>
                        <h2 style="font-size:1.05rem;font-weight:700;color:#0f172a;margin:0 0 2px">Account Details</h2>
                        <div style="font-size:0.82rem;color:#64748b">Update your full name and administrative email address</div>
                    </div>
                    <span style="font-size:1.2rem">✏️</span>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px" class="admin-profile-fields-grid">
                    <div>
                        <label style="display:block;font-size:0.84rem;font-weight:600;color:#334155;margin-bottom:6px">
                            Full Name <span style="color:#ef4444">*</span>
                        </label>
                        <input type="text"
                               name="name"
                               value="{{ old('name', $user->name) }}"
                               required
                               class="form-control"
                               style="width:100%;height:42px;border-radius:8px;border:1.5px solid {{ $errors->has('name') ? '#f87171' : '#cbd5e1' }};padding:0 12px;font-size:0.88rem">
                        @error('name')
                            <div style="color:#ef4444;font-size:0.78rem;margin-top:4px">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label style="display:block;font-size:0.84rem;font-weight:600;color:#334155;margin-bottom:6px">
                            Email Address <span style="color:#ef4444">*</span>
                        </label>
                        <input type="email"
                               name="email"
                               value="{{ old('email', $user->email) }}"
                               required
                               class="form-control"
                               style="width:100%;height:42px;border-radius:8px;border:1.5px solid {{ $errors->has('email') ? '#f87171' : '#cbd5e1' }};padding:0 12px;font-size:0.88rem">
                        @error('email')
                            <div style="color:#ef4444;font-size:0.78rem;margin-top:4px">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Card 2: Security & Password -->
            <div class="card" style="padding:24px 26px;border-radius:14px;border:1px solid #e2e8f0;background:#ffffff;box-shadow:0 1px 3px rgba(0,0,0,0.03)">
                <div style="border-bottom:1px solid #f1f5f9;padding-bottom:14px;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between">
                    <div>
                        <h2 style="font-size:1.05rem;font-weight:700;color:#0f172a;margin:0 0 2px">Change Password</h2>
                        <div style="font-size:0.82rem;color:#64748b">Leave blank if you don't wish to change your password</div>
                    </div>
                    <span style="font-size:1.2rem">🔒</span>
                </div>

                <div style="display:flex;flex-direction:column;gap:16px">
                    <div>
                        <label style="display:block;font-size:0.84rem;font-weight:600;color:#334155;margin-bottom:6px">
                            Current Password
                        </label>
                        <input type="password"
                               name="current_password"
                               autocomplete="current-password"
                               placeholder="Enter your existing password to verify"
                               class="form-control"
                               style="width:100%;height:42px;border-radius:8px;border:1.5px solid {{ $errors->has('current_password') ? '#f87171' : '#cbd5e1' }};padding:0 12px;font-size:0.88rem">
                        @error('current_password')
                            <div style="color:#ef4444;font-size:0.78rem;margin-top:4px">{{ $message }}</div>
                        @enderror
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px" class="admin-profile-fields-grid">
                        <div>
                            <label style="display:block;font-size:0.84rem;font-weight:600;color:#334155;margin-bottom:6px">
                                New Password
                            </label>
                            <input type="password"
                                   name="password"
                                   autocomplete="new-password"
                                   placeholder="Minimum 8 characters"
                                   class="form-control"
                                   style="width:100%;height:42px;border-radius:8px;border:1.5px solid {{ $errors->has('password') ? '#f87171' : '#cbd5e1' }};padding:0 12px;font-size:0.88rem">
                            @error('password')
                                <div style="color:#ef4444;font-size:0.78rem;margin-top:4px">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label style="display:block;font-size:0.84rem;font-weight:600;color:#334155;margin-bottom:6px">
                                Confirm New Password
                            </label>
                            <input type="password"
                                   name="password_confirmation"
                                   autocomplete="new-password"
                                   placeholder="Repeat new password"
                                   class="form-control"
                                   style="width:100%;height:42px;border-radius:8px;border:1.5px solid #cbd5e1;padding:0 12px;font-size:0.88rem">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Action -->
            <div class="admin-profile-actions" style="display:flex;justify-content:flex-end;gap:12px;margin-top:4px">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary admin-profile-cancel-btn" style="border-radius:8px;padding:10px 20px;font-weight:600">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary admin-profile-save-btn" style="background:#2563eb;border-color:#2563eb;border-radius:8px;padding:10px 24px;font-weight:700;box-shadow:0 2px 8px rgba(37,99,235,0.25)">
                    <span>💾 Save Profile Changes</span>
                </button>
            </div>

        </div>

    </div>
</form>

<style>
@media (max-width: 900px) {
    .admin-profile-grid {
        grid-template-columns: 1fr !important;
        gap: 16px !important;
    }
}
@media (max-width: 640px) {
    .admin-profile-fields-grid {
        grid-template-columns: 1fr !important;
        gap: 14px !important;
    }
    .admin-profile-actions {
        flex-direction: column-reverse !important;
        width: 100% !important;
    }
    .admin-profile-actions .btn {
        width: 100% !important;
        justify-content: center !important;
    }
    .card {
        padding: 18px 14px !important;
    }
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const avatarInput = document.getElementById('avatarInput');
    const avatarPreview = document.getElementById('avatarPreview');
    const removeCheck = document.getElementById('removeAvatarCheck');

    if (avatarInput && avatarPreview) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    avatarPreview.src = evt.target.result;
                    if (removeCheck) removeCheck.checked = false;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    if (removeCheck) {
        removeCheck.addEventListener('change', function() {
            if (this.checked) {
                avatarPreview.src = 'https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=1d4ed8&color=ffffff&bold=true&size=128';
                if (avatarInput) avatarInput.value = '';
            }
        });
    }
});
</script>
@endpush
@endsection
