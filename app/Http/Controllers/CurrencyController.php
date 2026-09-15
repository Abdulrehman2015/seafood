<?php

namespace App\Http\Controllers;

use App\Services\CurrencyService;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function __construct(
        protected CurrencyService $currencyService
    ) {}

    /**
     * Switch current currency.
     */
    public function switch(Request $request, ?string $code = null)
    {
        $rawCode = $code ?: ($request->input('currency') ?: ($request->input('code') ?: $request->query('currency', $request->getQueryString())));
        $currencyCode = strtoupper(preg_replace('/[^a-zA-Z]/', '', (string) $rawCode));
        if (strlen($currencyCode) > 3) {
            $currencyCode = substr($currencyCode, 0, 3);
        }
        $currencyCode = $currencyCode ?: 'MYR';

        if ($this->currencyService->setCurrency($currencyCode)) {
            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success'  => true,
                    'currency' => $currencyCode,
                    'symbol'   => $this->currencyService->getSymbol($currencyCode),
                    'label'    => $this->currencyService->getLabel($currencyCode),
                    'is_auto'  => $this->currencyService->isAutoConvert(),
                    'rates'    => $this->currencyService->getRates(),
                ]);
            }

            return back()->with('success', "Currency switched to {$currencyCode} (" . $this->currencyService->getSymbol($currencyCode) . ").");
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => false, 'message' => 'Invalid currency selected.'], 422);
        }

        return back()->with('error', 'Invalid currency selected.');
    }

    /**
     * Admin action to manually sync exchange rates from API.
     */
    public function syncRates(Request $request)
    {
        $rates = $this->currencyService->syncRates();
        $sgdFormatted = number_format($rates['SGD'], 4);
        $usdFormatted = number_format($rates['USD'], 4);
        $msg = "Live exchange rates synced successfully: 1 MYR = {$sgdFormatted} SGD | 1 MYR = {$usdFormatted} USD.";

        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'         => true,
                'message'         => $msg,
                'rates'           => $rates,
                'rates_formatted' => [
                    'SGD' => $sgdFormatted,
                    'USD' => $usdFormatted,
                ],
                'updated_at'      => now()->format('d M Y, h:i A'),
            ]);
        }

        return redirect()->route('admin.settings.index', ['tab' => 'currency'])
            ->with('success', $msg)
            ->with('tab', 'currency');
    }
}
