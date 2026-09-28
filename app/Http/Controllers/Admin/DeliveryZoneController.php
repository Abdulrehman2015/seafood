<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryZone;
use App\Models\Setting;
use Illuminate\Http\Request;

class DeliveryZoneController extends Controller
{
    public function index()
    {
        $zones = DeliveryZone::orderBy('sort_order')->orderBy('id')->get();
        $b2cThreshold = (float) Setting::get('delivery_b2c_free_threshold', 100.00);

        return view('admin.delivery_zones.index', compact('zones', 'b2cThreshold'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                      => 'required|string|max:255',
            'code'                      => 'required|string|max:50|unique:delivery_zones,code',
            'description'               => 'nullable|string|max:1000',
            'postcodes'                 => 'nullable|string',
            'areas'                     => 'nullable|string',
            'states'                    => 'nullable|string',
            'delivery_fee'              => 'required|numeric|min:0',
            'below_threshold_fee'       => 'required|numeric|min:0',
            'is_b2c_enabled'            => 'nullable|boolean',
            'is_b2b_enabled'            => 'nullable|boolean',
            'is_trading_enabled'        => 'nullable|boolean',
            'is_active'                 => 'nullable|boolean',
            'manual_quotation_required' => 'nullable|boolean',
            'sort_order'                => 'nullable|integer|min:0',
            'notes'                     => 'nullable|string|max:2000',
        ]);

        $validated['is_b2c_enabled']            = $request->boolean('is_b2c_enabled', true);
        $validated['is_b2b_enabled']            = $request->boolean('is_b2b_enabled', false);
        $validated['is_trading_enabled']        = $request->boolean('is_trading_enabled', false);
        $validated['is_active']                 = $request->boolean('is_active', true);
        $validated['manual_quotation_required'] = $request->boolean('manual_quotation_required', false);
        $validated['sort_order']                = (int) ($request->input('sort_order', 0));

        $zone = DeliveryZone::create($validated);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Delivery zone created successfully.', 'zone' => $zone]);
        }

        return redirect()->route('admin.delivery-zones.index')->with('success', "Delivery Zone '{$zone->name}' created successfully.");
    }

    public function update(Request $request, DeliveryZone $deliveryZone)
    {
        $validated = $request->validate([
            'name'                      => 'required|string|max:255',
            'code'                      => 'required|string|max:50|unique:delivery_zones,code,' . $deliveryZone->id,
            'description'               => 'nullable|string|max:1000',
            'postcodes'                 => 'nullable|string',
            'areas'                     => 'nullable|string',
            'states'                    => 'nullable|string',
            'delivery_fee'              => 'required|numeric|min:0',
            'below_threshold_fee'       => 'required|numeric|min:0',
            'is_b2c_enabled'            => 'nullable|boolean',
            'is_b2b_enabled'            => 'nullable|boolean',
            'is_trading_enabled'        => 'nullable|boolean',
            'is_active'                 => 'nullable|boolean',
            'manual_quotation_required' => 'nullable|boolean',
            'sort_order'                => 'nullable|integer|min:0',
            'notes'                     => 'nullable|string|max:2000',
        ]);

        $validated['is_b2c_enabled']            = $request->boolean('is_b2c_enabled');
        $validated['is_b2b_enabled']            = $request->boolean('is_b2b_enabled');
        $validated['is_trading_enabled']        = $request->boolean('is_trading_enabled');
        $validated['is_active']                 = $request->boolean('is_active');
        $validated['manual_quotation_required'] = $request->boolean('manual_quotation_required');
        $validated['sort_order']                = (int) ($request->input('sort_order', 0));

        $deliveryZone->update($validated);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Delivery zone updated successfully.', 'zone' => $deliveryZone]);
        }

        return redirect()->route('admin.delivery-zones.index')->with('success', "Delivery Zone '{$deliveryZone->name}' updated successfully.");
    }

    public function toggleStatus(DeliveryZone $deliveryZone)
    {
        $deliveryZone->update(['is_active' => !$deliveryZone->is_active]);

        $statusLabel = $deliveryZone->is_active ? 'activated' : 'deactivated';
        return redirect()->route('admin.delivery-zones.index')->with('success', "Zone '{$deliveryZone->name}' has been {$statusLabel}.");
    }

    public function updateThreshold(Request $request)
    {
        $request->validate([
            'delivery_b2c_free_threshold' => 'required|numeric|min:0',
        ]);

        Setting::set('delivery_b2c_free_threshold', $request->input('delivery_b2c_free_threshold'));

        return redirect()->route('admin.delivery-zones.index')->with('success', 'B2C Delivery Fee Threshold updated successfully.');
    }

    public function destroy(DeliveryZone $deliveryZone)
    {
        $name = $deliveryZone->name;
        $deliveryZone->delete();

        return redirect()->route('admin.delivery-zones.index')->with('success', "Delivery Zone '{$name}' deleted successfully.");
    }
}
