@extends('layouts.admin')
@section('title', 'Add Customer Review — Admin')

@section('content')
<div class="admin-topbar" style="margin-bottom:var(--space-6)">
    <div>
        <h1 class="admin-page-title" style="margin-bottom:4px">Add Customer Review</h1>
        <p class="text-sm text-muted" style="margin:0">Create a new customer testimonial or client review to display on the storefront.</p>
    </div>
    <div>
        <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary btn-sm">
            ← Back to Reviews
        </a>
    </div>
</div>

<style>
.rev-form-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}
@media (max-width: 640px) {
    .rev-form-grid-2 {
        grid-template-columns: 1fr !important;
        gap: 14px !important;
    }
}
</style>

<div class="card" style="background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,0.03);max-width:760px;padding:clamp(16px, 4vw, 28px)">
    <form action="{{ route('admin.reviews.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="rev-form-grid-2">
            <!-- Customer Name -->
            <div>
                <label style="display:block;font-size:0.85rem;font-weight:600;color:#334155;margin-bottom:6px">
                    Customer / Client Name <span style="color:#ef4444">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Chef Marcus Tan" class="form-control" style="border-radius:8px">
                @error('name')<div style="color:#ef4444;font-size:0.8rem;margin-top:4px">{{ $message }}</div>@enderror
            </div>

            <!-- Role / Company -->
            <div>
                <label style="display:block;font-size:0.85rem;font-weight:600;color:#334155;margin-bottom:6px">
                    Role / Company / Location
                </label>
                <input type="text" name="role_or_company" value="{{ old('role_or_company') }}" placeholder="e.g. Executive Chef, Marina Bistro" class="form-control" style="border-radius:8px">
                @error('role_or_company')<div style="color:#ef4444;font-size:0.8rem;margin-top:4px">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="rev-form-grid-2">
            <!-- Rating -->
            <div>
                <label style="display:block;font-size:0.85rem;font-weight:600;color:#334155;margin-bottom:6px">
                    Rating <span style="color:#ef4444">*</span>
                </label>
                <select name="rating" class="form-control" style="border-radius:8px;font-weight:600">
                    <option value="5" {{ old('rating', '5') == '5' ? 'selected' : '' }}>★★★★★ 5 Stars (Excellent)</option>
                    <option value="4" {{ old('rating') == '4' ? 'selected' : '' }}>★★★★☆ 4 Stars (Very Good)</option>
                    <option value="3" {{ old('rating') == '3' ? 'selected' : '' }}>★★★☆☆ 3 Stars (Good)</option>
                    <option value="2" {{ old('rating') == '2' ? 'selected' : '' }}>★★☆☆☆ 2 Stars (Fair)</option>
                    <option value="1" {{ old('rating') == '1' ? 'selected' : '' }}>★☆☆☆☆ 1 Star (Poor)</option>
                </select>
                @error('rating')<div style="color:#ef4444;font-size:0.8rem;margin-top:4px">{{ $message }}</div>@enderror
            </div>

            <!-- Status -->
            <div>
                <label style="display:block;font-size:0.85rem;font-weight:600;color:#334155;margin-bottom:6px">
                    Moderation Status <span style="color:#ef4444">*</span>
                </label>
                <select name="status" class="form-control" style="border-radius:8px">
                    <option value="approved" {{ old('status', 'approved') === 'approved' ? 'selected' : '' }}>Approved (Visible on website)</option>
                    <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pending Moderation (Hidden)</option>
                </select>
                @error('status')<div style="color:#ef4444;font-size:0.8rem;margin-top:4px">{{ $message }}</div>@enderror
            </div>
        </div>

        <!-- Feedback / Comment -->
        <div style="margin-bottom:20px">
            <label style="display:block;font-size:0.85rem;font-weight:600;color:#334155;margin-bottom:6px">
                Review / Testimonial Text <span style="color:#ef4444">*</span>
            </label>
            <textarea name="comment" rows="4" required placeholder="Write the customer testimonial or feedback here..." class="form-control" style="border-radius:8px;line-height:1.6">{{ old('comment') }}</textarea>
            @error('comment')<div style="color:#ef4444;font-size:0.8rem;margin-top:4px">{{ $message }}</div>@enderror
        </div>

        <!-- Customer Avatar Upload -->
        <div style="margin-bottom:24px">
            <label style="display:block;font-size:0.85rem;font-weight:600;color:#334155;margin-bottom:6px">
                Customer Avatar / Photo (Optional)
            </label>
            <div style="display:flex;align-items:center;gap:16px">
                <div id="avatarPreviewContainer" style="width:56px;height:56px;border-radius:50%;background:#f1f5f9;border:1px dashed #cbd5e1;display:flex;align-items:center;justify-content:center;overflow:hidden">
                    <span id="avatarPlaceholder" style="font-size:1.5rem">👤</span>
                    <img id="avatarPreview" src="" alt="Preview" style="width:100%;height:100%;object-fit:cover;display:none">
                </div>
                <div style="flex:1">
                    <input type="file" name="avatar" id="avatarInput" accept="image/*" class="form-control" style="border-radius:8px;font-size:0.85rem">
                    <span style="font-size:0.75rem;color:#64748b;display:block;margin-top:4px">PNG, JPG, or WebP. Max 2MB. If empty, stylish customer initials will be automatically generated.</span>
                </div>
            </div>
            @error('avatar')<div style="color:#ef4444;font-size:0.8rem;margin-top:4px">{{ $message }}</div>@enderror
        </div>

        <!-- Options: Featured & Sort Order -->
        <div class="rev-form-grid-2" style="padding:16px;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0;margin-bottom:24px">
            <div>
                <label style="display:flex;align-items:center;gap:8px;font-size:0.9rem;font-weight:600;color:#1e293b;cursor:pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', '1') ? 'checked' : '' }} style="width:18px;height:18px;border-radius:4px;accent-color:#0d9488">
                    Featured Review
                </label>
                <span style="font-size:0.75rem;color:#64748b;display:block;margin-top:4px">Featured reviews appear prioritized on the Homepage and key landing pages.</span>
            </div>
            <div>
                <label style="display:block;font-size:0.85rem;font-weight:600;color:#334155;margin-bottom:4px">
                    Display Sort Order
                </label>
                <input type="number" name="sort_order" value="{{ old('sort_order', '0') }}" class="form-control" style="border-radius:8px;max-width:140px">
                <span style="font-size:0.75rem;color:#64748b;display:block;margin-top:4px">Lower numbers appear first (e.g. 0, 1, 2).</span>
            </div>
        </div>

        <div style="display:flex;gap:12px">
            <button type="submit" class="btn btn-primary btn-lg" style="font-weight:700;padding:10px 24px">
                ✓ Save Customer Review
            </button>
            <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary btn-lg">
                Cancel
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.getElementById('avatarInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(evt) {
            const preview = document.getElementById('avatarPreview');
            const placeholder = document.getElementById('avatarPlaceholder');
            preview.src = evt.target.result;
            preview.style.display = 'block';
            placeholder.style.display = 'none';
        }
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
@endsection
