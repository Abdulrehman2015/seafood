@extends('layouts.admin')
@section('title', 'Delivery Zones & Logistics — Admin')

@section('content')
<!-- Page Top Header -->
<div class="admin-topbar" style="margin-bottom:var(--space-5)">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px">
        <div>
            <h1 class="admin-page-title" style="font-size:1.5rem;display:flex;align-items:center;gap:8px">
                <span>🚚</span> Delivery Zones &amp; Transportation Rules
            </h1>
            <p class="text-sm text-muted" style="margin:4px 0 0">
                Configure backend delivery zones, postcodes, area mappings, and below-RM100 transport fee rules.
            </p>
        </div>
        <div style="display:flex;gap:10px;align-items:center">
            <button type="button" class="btn btn-primary" onclick="openCreateZoneModal()" style="background:#2563eb;border-color:#2563eb;font-weight:700;padding:9px 18px;border-radius:9px;display:inline-flex;align-items:center;gap:6px;box-shadow:0 2px 6px rgba(37,99,235,0.25);cursor:pointer">
                <span>➕</span> Add New Delivery Zone
            </button>
        </div>
    </div>
</div>

<!-- Quick Stats & Threshold Control Bar -->
<div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(240px, 1fr));gap:16px;margin-bottom:24px">
    
    <!-- Threshold Card with Quick Update Form -->
    <div style="background:white;border:1.5px solid #e2e8f0;border-radius:14px;padding:18px;box-shadow:0 1px 3px rgba(0,0,0,0.04)">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px">
            <span style="font-size:0.75rem;font-weight:800;color:#0284c7;text-transform:uppercase;letter-spacing:0.5px">B2C Delivery Fee Threshold</span>
            <span style="font-size:1.2rem">📦</span>
        </div>
        <form action="{{ route('admin.delivery-zones.updateThreshold') }}" method="POST" style="display:flex;gap:8px;align-items:center;margin-top:8px">
            @csrf
            <div style="position:relative;flex:1">
                <span style="position:absolute;left:10px;top:50%;transform:translateY(-50%);font-weight:700;color:#0284c7;font-size:0.85rem">RM</span>
                <input type="number" step="0.01" min="0" name="delivery_b2c_free_threshold" value="{{ number_format($b2cThreshold, 2, '.', '') }}" required
                       style="padding-left:36px;height:38px;font-weight:800;font-size:1rem;color:#0f172a;border:1.5px solid #bae6fd;border-radius:8px;width:100%;box-sizing:border-box">
            </div>
            <button type="submit" class="btn btn-sm" style="background:#0284c7;color:#ffffff;font-weight:700;padding:8px 14px;border-radius:8px;border:none;cursor:pointer;white-space:nowrap">
                Save
            </button>
        </form>
        <div style="font-size:0.72rem;color:#64748b;margin-top:6px">
            Orders below this amount incur the configured zone transportation charge.
        </div>
    </div>

    <!-- Active Zones Count -->
    <div style="background:white;border:1.5px solid #e2e8f0;border-radius:14px;padding:18px;box-shadow:0 1px 3px rgba(0,0,0,0.04)">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px">
            <span style="font-size:0.75rem;font-weight:800;color:#059669;text-transform:uppercase;letter-spacing:0.5px">Active Delivery Zones</span>
            <span style="font-size:1.2rem">🗺️</span>
        </div>
        <div style="font-size:1.6rem;font-weight:900;color:#0f172a;font-family:monospace">
            {{ $zones->where('is_active', true)->count() }} <span style="font-size:0.85rem;color:#64748b;font-weight:normal">/ {{ $zones->count() }} total</span>
        </div>
        <div style="font-size:0.72rem;color:#059669;margin-top:4px;font-weight:600">
            ● {{ $zones->where('is_b2c_enabled', true)->count() }} enabled for B2C Retail
        </div>
    </div>

    <!-- Walk-in Rule Card -->
    <div style="background:white;border:1.5px solid #e2e8f0;border-radius:14px;padding:18px;box-shadow:0 1px 3px rgba(0,0,0,0.04)">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px">
            <span style="font-size:0.75rem;font-weight:800;color:#4f46e5;text-transform:uppercase;letter-spacing:0.5px">Counter 2 Walk-in Collection</span>
            <span style="font-size:1.2rem">🏪</span>
        </div>
        <div style="font-size:1.6rem;font-weight:900;color:#4f46e5;font-family:monospace">
            RM 0.00 <span style="font-size:0.85rem;color:#16a34a;font-weight:700">(Always Free)</span>
        </div>
        <div style="font-size:0.72rem;color:#64748b;margin-top:4px">
            No threshold or postcode requirement applied.
        </div>
    </div>

</div>

<!-- Delivery Zones List Card -->
<div class="card" style="background:white;border-radius:14px;border:1.5px solid #e2e8f0;box-shadow:0 1px 4px rgba(0,0,0,0.04);overflow:hidden">
    <div style="padding:18px 22px;border-bottom:1.5px solid #e2e8f0;background:#f8fafc;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px">
        <div>
            <h2 style="font-size:1.05rem;font-weight:800;color:#0f172a;margin:0">Configured Delivery Zones</h2>
            <p style="font-size:0.78rem;color:#64748b;margin:2px 0 0">
                Zones are matched top-to-bottom by postcode, area/city name, and state.
            </p>
        </div>
        <span style="font-size:0.75rem;color:#64748b">
            Showing {{ $zones->count() }} {{ Str::plural('zone', $zones->count()) }}
        </span>
    </div>

    @if($zones->isEmpty())
    <div style="padding:40px 20px;text-align:center;color:#64748b">
        <div style="font-size:2.5rem;margin-bottom:8px">🚚</div>
        <h3 style="font-size:1.1rem;font-weight:700;color:#0f172a;margin-bottom:4px">No Delivery Zones Configured</h3>
        <p style="font-size:0.85rem;max-width:460px;margin:0 auto 16px">
            Create delivery zones to specify postcodes, area boundaries, standard fees, and below-RM100 additional rates.
        </p>
        <button type="button" class="btn btn-primary" onclick="openCreateZoneModal()" style="font-weight:700;border-radius:8px">
            ➕ Create First Delivery Zone
        </button>
    </div>
    @else
    <div class="table-responsive" style="overflow-x:auto">
        <table class="table" style="width:100%;margin:0;font-size:0.85rem;border-collapse:collapse">
            <thead>
                <tr style="background:#f8fafc;border-bottom:1.5px solid #e2e8f0;color:#475569;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">
                    <th style="padding:12px 16px;text-align:center;width:50px">Order</th>
                    <th style="padding:12px 16px">Zone Name &amp; Code</th>
                    <th style="padding:12px 16px">Postcodes &amp; Areas</th>
                    <th style="padding:12px 16px;text-align:right">Base Delivery Fee</th>
                    <th style="padding:12px 16px;text-align:right">Below-RM100 Fee</th>
                    <th style="padding:12px 16px;text-align:center">Customer Tiers</th>
                    <th style="padding:12px 16px;text-align:center">Status</th>
                    <th style="padding:12px 16px;text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($zones as $z)
                <tr style="border-bottom:1px solid #f1f5f9;transition:background 0.15s ease;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                    
                    {{-- Sort Order --}}
                    <td style="padding:14px 16px;text-align:center;font-weight:700;color:#64748b">
                        {{ $z->sort_order }}
                    </td>

                    {{-- Zone Info --}}
                    <td style="padding:14px 16px">
                        <div style="font-weight:800;color:#0f172a;font-size:0.92rem;margin-bottom:2px">
                            {{ $z->name }}
                        </div>
                        <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap">
                            <span style="font-family:monospace;font-size:0.72rem;background:#e0f2fe;color:#0369a1;padding:1px 6px;border-radius:4px;font-weight:700">
                                {{ $z->code }}
                            </span>
                            @if($z->manual_quotation_required)
                                <span style="font-size:0.7rem;background:#fef3c7;color:#b45309;padding:1px 6px;border-radius:4px;font-weight:700;border:1px solid #fde68a">
                                    ⚠️ Manual Quote Required
                                </span>
                            @endif
                        </div>
                        @if($z->description)
                            <div style="font-size:0.75rem;color:#64748b;margin-top:4px;line-height:1.35">
                                {{ $z->description }}
                            </div>
                        @endif
                    </td>

                    {{-- Postcodes & Areas --}}
                    <td style="padding:14px 16px;max-width:280px">
                        @if($z->areas)
                            <div style="font-size:0.78rem;font-weight:600;color:#334155;margin-bottom:3px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="{{ $z->areas }}">
                                📍 {{ $z->areas }}
                            </div>
                        @endif
                        @if($z->postcodes)
                            @php
                                $postcodeList = array_filter(array_map('trim', preg_split('/[\r\n,]+/', $z->postcodes)));
                                $samplePostcodes = array_slice($postcodeList, 0, 5);
                            @endphp
                            <div style="font-size:0.72rem;color:#64748b">
                                📮 {{ implode(', ', $samplePostcodes) }} {{ count($postcodeList) > 5 ? '... (+' . (count($postcodeList) - 5) . ' more)' : '' }}
                            </div>
                        @endif
                        @if($z->states)
                            <div style="font-size:0.72rem;color:#0284c7;margin-top:2px">
                                🗺️ {{ $z->states }}
                            </div>
                        @endif
                    </td>

                    {{-- Base Fee --}}
                    <td style="padding:14px 16px;text-align:right">
                        @if($z->delivery_fee <= 0)
                            <span style="font-weight:800;color:#16a34a;background:#ecfdf5;padding:3px 8px;border-radius:6px;font-size:0.82rem">
                                RM 0.00 (Free)
                            </span>
                        @else
                            <span style="font-weight:800;color:#0f172a;font-family:monospace;font-size:0.95rem">
                                RM {{ number_format($z->delivery_fee, 2) }}
                            </span>
                        @endif
                    </td>

                    {{-- Below-Threshold Fee --}}
                    <td style="padding:14px 16px;text-align:right">
                        @if($z->below_threshold_fee <= 0)
                            <span style="color:#94a3b8;font-size:0.78rem">None (RM 0)</span>
                        @else
                            <span style="font-weight:800;color:#d97706;font-family:monospace;font-size:0.95rem;background:#fffbeb;border:1px solid #fde68a;padding:2px 8px;border-radius:6px">
                                + RM {{ number_format($z->below_threshold_fee, 2) }}
                            </span>
                        @endif
                    </td>

                    {{-- Customer Tiers --}}
                    <td style="padding:14px 16px;text-align:center">
                        <div style="display:inline-flex;gap:4px;flex-wrap:wrap;justify-content:center">
                            <span style="font-size:0.7rem;font-weight:700;padding:2px 6px;border-radius:4px;{{ $z->is_b2c_enabled ? 'background:#dcfce7;color:#166534;' : 'background:#f1f5f9;color:#94a3b8;' }}">
                                B2C
                            </span>
                            <span style="font-size:0.7rem;font-weight:700;padding:2px 6px;border-radius:4px;{{ $z->is_b2b_enabled ? 'background:#dbeafe;color:#1e40af;' : 'background:#f1f5f9;color:#94a3b8;' }}">
                                B2B
                            </span>
                            <span style="font-size:0.7rem;font-weight:700;padding:2px 6px;border-radius:4px;{{ $z->is_trading_enabled ? 'background:#ede9fe;color:#5b21b6;' : 'background:#f1f5f9;color:#94a3b8;' }}">
                                Trading
                            </span>
                        </div>
                    </td>

                    {{-- Status Toggle --}}
                    <td style="padding:14px 16px;text-align:center">
                        <form action="{{ route('admin.delivery-zones.toggleStatus', $z) }}" method="POST" style="margin:0">
                            @csrf
                            <button type="submit" class="btn btn-sm" style="font-size:0.72rem;font-weight:700;padding:3px 10px;border-radius:20px;border:1px solid;cursor:pointer;{{ $z->is_active ? 'background:#ecfdf5;color:#047857;border-color:#a7f3d0' : 'background:#fef2f2;color:#b91c1c;border-color:#fecaca' }}">
                                {{ $z->is_active ? '● Active' : '○ Inactive' }}
                            </button>
                        </form>
                    </td>

                    {{-- Actions --}}
                    <td style="padding:14px 16px;text-align:right">
                        <div style="display:inline-flex;gap:6px;align-items:center">
                            <button type="button" class="btn btn-secondary btn-sm" onclick="openEditZoneModal({{ json_encode($z) }})" style="font-size:0.78rem;padding:4px 10px;border-radius:6px;font-weight:700">
                                ✏️ Edit
                            </button>
                            <form action="{{ route('admin.delivery-zones.destroy', $z) }}" method="POST" style="margin:0" onsubmit="return confirm('Are you sure you want to delete delivery zone \'{{ addslashes($z->name) }}\'?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="background:#ffffff;border:1px solid #ef4444;color:#ef4444;font-size:0.78rem;padding:4px 8px;border-radius:6px;font-weight:700;cursor:pointer">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

<!-- Modal: Create / Edit Delivery Zone -->
<div id="zoneModal" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.6);backdrop-filter:blur(4px);z-index:9999;align-items:center;justify-content:center;padding:16px;overflow-y:auto">
    <div style="background:#ffffff;border-radius:16px;max-width:680px;width:100%;box-shadow:0 20px 25px -5px rgba(0,0,0,0.15);overflow:hidden;margin:auto">
        
        <!-- Modal Header -->
        <div style="padding:16px 22px;border-bottom:1px solid #e2e8f0;background:#f8fafc;display:flex;align-items:center;justify-content:space-between">
            <h3 id="zoneModalTitle" style="font-size:1.1rem;font-weight:800;color:#0f172a;margin:0">
                Add New Delivery Zone
            </h3>
            <button type="button" onclick="closeZoneModal()" style="border:none;background:transparent;font-size:1.2rem;cursor:pointer;color:#64748b;line-height:1">✕</button>
        </div>

        <!-- Modal Form -->
        <form id="zoneForm" action="{{ route('admin.delivery-zones.store') }}" method="POST" style="padding:22px">
            @csrf
            <input type="hidden" name="_method" id="zoneFormMethod" value="POST">

            <div style="display:grid;grid-template-columns:2fr 1fr;gap:14px;margin-bottom:14px">
                <div class="form-group mb-0">
                    <label class="form-label" style="font-weight:700;font-size:0.85rem">Zone Name <span style="color:#ef4444">*</span></label>
                    <input type="text" name="name" id="input_name" class="form-control" placeholder="e.g. Zone A - Johor Bahru" required style="border-radius:8px">
                </div>
                <div class="form-group mb-0">
                    <label class="form-label" style="font-weight:700;font-size:0.85rem">Zone Code <span style="color:#ef4444">*</span></label>
                    <input type="text" name="code" id="input_code" class="form-control" placeholder="e.g. ZONE-A" required style="border-radius:8px;text-transform:uppercase">
                </div>
            </div>

            <div class="form-group mb-3">
                <label class="form-label" style="font-weight:700;font-size:0.85rem">Description</label>
                <input type="text" name="description" id="input_description" class="form-control" placeholder="e.g. Selected Johor Bahru & Iskandar Puteri areas" style="border-radius:8px">
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px">
                <div class="form-group mb-0">
                    <label class="form-label" style="font-weight:700;font-size:0.85rem">Normal Delivery Fee (RM) <span style="color:#ef4444">*</span></label>
                    <input type="number" step="0.01" min="0" name="delivery_fee" id="input_delivery_fee" class="form-control" value="0.00" required style="border-radius:8px;font-weight:700">
                    <div style="font-size:0.72rem;color:#64748b;margin-top:3px">Standard base fee (set to 0 for free standard delivery)</div>
                </div>
                <div class="form-group mb-0">
                    <label class="form-label" style="font-weight:700;font-size:0.85rem">Below-RM100 Additional Fee (RM) <span style="color:#ef4444">*</span></label>
                    <input type="number" step="0.01" min="0" name="below_threshold_fee" id="input_below_threshold_fee" class="form-control" value="10.00" required style="border-radius:8px;font-weight:700;color:#d97706">
                    <div style="font-size:0.72rem;color:#64748b;margin-top:3px">Additional charge applied when cart total &lt; RM100</div>
                </div>
            </div>

            <div class="form-group mb-3">
                <label class="form-label" style="font-weight:700;font-size:0.85rem">
                    Postcodes <span style="font-weight:normal;color:#64748b">(Comma or newline separated, prefixes supported e.g. 79000, 79100, 80xxx)</span>
                </label>
                <textarea name="postcodes" id="input_postcodes" class="form-control" rows="3" placeholder="79000&#10;79100&#10;79200&#10;80000" style="border-radius:8px;font-family:monospace;font-size:0.82rem"></textarea>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px">
                <div class="form-group mb-0">
                    <label class="form-label" style="font-weight:700;font-size:0.85rem">Areas / Cities <span style="font-weight:normal;color:#64748b">(Comma separated)</span></label>
                    <input type="text" name="areas" id="input_areas" class="form-control" placeholder="Johor Bahru, Skudai, Iskandar Puteri" style="border-radius:8px">
                </div>
                <div class="form-group mb-0">
                    <label class="form-label" style="font-weight:700;font-size:0.85rem">States <span style="font-weight:normal;color:#64748b">(Comma separated)</span></label>
                    <input type="text" name="states" id="input_states" class="form-control" placeholder="Johor" style="border-radius:8px">
                </div>
            </div>

            <!-- Customer Tiers & Options Checkboxes -->
            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:14px;margin-bottom:16px">
                <div style="font-size:0.75rem;font-weight:800;color:#475569;text-transform:uppercase;margin-bottom:8px">
                    Tier Activation &amp; Arrangement Options
                </div>
                <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(140px, 1fr));gap:10px">
                    <label style="display:flex;align-items:center;gap:6px;font-size:0.85rem;cursor:pointer;font-weight:600">
                        <input type="checkbox" name="is_b2c_enabled" id="input_is_b2c_enabled" value="1" checked style="accent-color:#2563eb">
                        <span>B2C Retail</span>
                    </label>
                    <label style="display:flex;align-items:center;gap:6px;font-size:0.85rem;cursor:pointer;font-weight:600">
                        <input type="checkbox" name="is_b2b_enabled" id="input_is_b2b_enabled" value="1" style="accent-color:#2563eb">
                        <span>B2B Wholesale</span>
                    </label>
                    <label style="display:flex;align-items:center;gap:6px;font-size:0.85rem;cursor:pointer;font-weight:600">
                        <input type="checkbox" name="is_trading_enabled" id="input_is_trading_enabled" value="1" style="accent-color:#2563eb">
                        <span>Trading</span>
                    </label>
                    <label style="display:flex;align-items:center;gap:6px;font-size:0.85rem;cursor:pointer;font-weight:600">
                        <input type="checkbox" name="is_active" id="input_is_active" value="1" checked style="accent-color:#16a34a">
                        <span>Zone Active</span>
                    </label>
                </div>
                <div style="margin-top:10px;padding-top:10px;border-top:1px dashed #e2e8f0">
                    <label style="display:flex;align-items:center;gap:6px;font-size:0.85rem;cursor:pointer;font-weight:600;color:#b45309">
                        <input type="checkbox" name="manual_quotation_required" id="input_manual_quotation_required" value="1" style="accent-color:#ea580c">
                        <span>Require Manual Delivery Arrangement / Quotation</span>
                    </label>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:100px 1fr;gap:14px;margin-bottom:16px">
                <div class="form-group mb-0">
                    <label class="form-label" style="font-weight:700;font-size:0.85rem">Sort Order</label>
                    <input type="number" name="sort_order" id="input_sort_order" class="form-control" value="0" style="border-radius:8px;text-align:center">
                </div>
                <div class="form-group mb-0">
                    <label class="form-label" style="font-weight:700;font-size:0.85rem">Internal Notes</label>
                    <input type="text" name="notes" id="input_notes" class="form-control" placeholder="Optional admin notes..." style="border-radius:8px">
                </div>
            </div>

            <!-- Modal Action Buttons -->
            <div style="display:flex;justify-content:flex-end;gap:10px;padding-top:14px;border-top:1px solid #f1f5f9">
                <button type="button" class="btn btn-secondary" onclick="closeZoneModal()" style="border-radius:8px;font-weight:600">
                    Cancel
                </button>
                <button type="submit" class="btn btn-primary" id="btnSubmitZone" style="background:#2563eb;border-color:#2563eb;font-weight:700;border-radius:8px;padding:9px 22px">
                    💾 Save Delivery Zone
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openCreateZoneModal() {
    document.getElementById('zoneModalTitle').textContent = 'Add New Delivery Zone';
    const form = document.getElementById('zoneForm');
    form.action = '{{ route("admin.delivery-zones.store") }}';
    document.getElementById('zoneFormMethod').value = 'POST';

    form.reset();
    document.getElementById('input_is_b2c_enabled').checked = true;
    document.getElementById('input_is_active').checked = true;
    document.getElementById('input_manual_quotation_required').checked = false;
    document.getElementById('input_sort_order').value = '{{ $zones->count() + 1 }}';

    const modal = document.getElementById('zoneModal');
    modal.style.display = 'flex';
}

function openEditZoneModal(zone) {
    document.getElementById('zoneModalTitle').textContent = 'Edit Delivery Zone: ' + zone.name;
    const form = document.getElementById('zoneForm');
    form.action = '/admin/delivery-zones/' + zone.id;
    document.getElementById('zoneFormMethod').value = 'PATCH';

    document.getElementById('input_name').value = zone.name || '';
    document.getElementById('input_code').value = zone.code || '';
    document.getElementById('input_description').value = zone.description || '';
    document.getElementById('input_delivery_fee').value = parseFloat(zone.delivery_fee || 0).toFixed(2);
    document.getElementById('input_below_threshold_fee').value = parseFloat(zone.below_threshold_fee || 0).toFixed(2);
    document.getElementById('input_postcodes').value = zone.postcodes || '';
    document.getElementById('input_areas').value = zone.areas || '';
    document.getElementById('input_states').value = zone.states || '';
    document.getElementById('input_sort_order').value = zone.sort_order || 0;
    document.getElementById('input_notes').value = zone.notes || '';

    document.getElementById('input_is_b2c_enabled').checked = Boolean(zone.is_b2c_enabled);
    document.getElementById('input_is_b2b_enabled').checked = Boolean(zone.is_b2b_enabled);
    document.getElementById('input_is_trading_enabled').checked = Boolean(zone.is_trading_enabled);
    document.getElementById('input_is_active').checked = Boolean(zone.is_active);
    document.getElementById('input_manual_quotation_required').checked = Boolean(zone.manual_quotation_required);

    const modal = document.getElementById('zoneModal');
    modal.style.display = 'flex';
}

function closeZoneModal() {
    document.getElementById('zoneModal').style.display = 'none';
}

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeZoneModal();
    }
});
</script>
@endpush
