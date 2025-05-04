<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChapaController extends Controller
{
    public function callback(Request $request)
    {
        $tx_ref = $request->query('tx_ref') ?? $request->query('trx_ref') ?? $request->input('tx_ref') ?? $request->input('trx_ref');
        $status = $request->query('status') ?? $request->input('status');

        if (!$tx_ref) {
            return redirect()->route('home')->with('error', 'Payment verification failed: No transaction reference found.');
        }

        $subscription = Subscription::with('user')->where('transaction_reference', $tx_ref)->first();

        if (!$subscription && strpos($tx_ref, '-') !== false) {
            $tx_ref_parts = explode('-', $tx_ref, 2);
            $tx_ref_without_prefix = $tx_ref_parts[1] ?? $tx_ref;

            $subscription = Subscription::with('user')
                ->where('transaction_reference', 'like', '%' . $tx_ref_without_prefix)
                ->orderBy('created_at', 'desc')
                ->first();
        }

        if (!$subscription) {
            return redirect()->route('home')->with('error', 'Payment verification failed: No subscription found for this transaction.');
        }

        if ($subscription->status === 'active') {
            return redirect()->route('home')->with('success', 'Your subscription is already active.');
        }

        if ((env('APP_ENV') === 'local' || env('APP_DEBUG')) && (!empty($status) && strtolower($status) === 'success' || str_starts_with($tx_ref, 'TX-'))) {
            $startDate = now();
            $expiresAt = $startDate->copy()->addDays($subscription->duration_in_days);

            $subscription->update([
                'status' => 'active',
                'paid_at' => $startDate,
                'starts_at' => $startDate,
                'expires_at' => $expiresAt,
            ]);

            $subscription->user->update([
                'is_pro' => true,
                'pro_expires_at' => $expiresAt,
                'subscription_type' => 'chapa',
                'subscription_status' => 'active',
            ]);

            return redirect()->route('home')->with('success', 'Test payment successful and subscription activated.');
        }

        $response = Http::withToken(env('CHAPA_SECRET_KEY'))->get("https://api.chapa.co/v1/transaction/verify/{$tx_ref}");

        if ($response->successful() && $response['data']['status'] === 'success') {
            $startDate = now();
            $expiresAt = $startDate->copy()->addDays($subscription->duration_in_days);

            $subscription->update([
                'status' => 'active',
                'paid_at' => now(),
                'starts_at' => $startDate,
                'expires_at' => $expiresAt,
            ]);

            $subscription->user->update([
                'is_pro' => true,
                'pro_expires_at' => $expiresAt,
                'subscription_type' => 'chapa',
                'subscription_status' => 'active',
            ]);

            return redirect()->route('home')->with('success', 'Payment successful and subscription activated.');
        }

        $errorMsg = $response->json('message') ?? 'Payment verification failed';
        return redirect()->route('home')->with('error', $errorMsg);
    }
}
