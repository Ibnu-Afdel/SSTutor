<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Chapa\Chapa\Facades\Chapa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChapaController extends Controller
{
    public function initialize(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        // Get payment details
        $duration = $request->get('duration', 30);
        $amount = $duration == 30 ? 199 : 1999;

        // Prevent duplicate active subscriptions
        $activeSubscription = Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->first();

        if ($activeSubscription && !$user->is_pro) {
            return redirect()->route('subscribe.index')
                ->with('info', 'You already have an active subscription.');
        }

        // Cancel pending subscriptions
        Subscription::where('user_id', $user->id)
            ->where('payment_method', 'chapa')
            ->where('status', 'pending')
            ->update([
                'status' => 'cancelled',
                'notes' => 'Cancelled due to new payment attempt'
            ]);

        // Create new subscription
        $tx_ref = 'TX-' . Str::uuid();
        
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'payment_method' => 'chapa',
            'status' => 'pending',
            'amount' => $amount,
            'duration_in_days' => $duration,
            'transaction_reference' => $tx_ref,
        ]);

        // Initialize Chapa payment
        $paymentData = [
            'amount' => $amount,
            'currency' => 'ETB',
            'email' => $user->email,
            'first_name' => $user->name,
            'tx_ref' => $tx_ref,
            
            // callback_url: Where Chapa sends server-to-server notification about payment status
            // return_url: Where user is redirected after completing payment on Chapa's page
            // Both point to same route because our verify() method handles both scenarios
            'callback_url' => route('chapa.verify'),
            'return_url' => route('chapa.verify'),
            
            'customization' => [
                'title' => 'Pro Subscription',
                'description' => $duration == 30 ? 'SSTutor Monthly Pro Access' : 'SSTutor Yearly Pro Access',
            ]
        ];

        $response = Chapa::initializePayment($paymentData);

        if ($response['status'] === 'success') {
            return redirect($response['data']['checkout_url']);
        }

        // Handle failure
        $subscription->update([
            'status' => 'failed',
            'notes' => $response['message'] ?? 'Payment initialization failed'
        ]);

        return redirect()->route('subscribe.index')
            ->with('error', 'Payment initialization failed. Please try again.');
    }

    public function verify(Request $request)
    {
        // This method handles both:
        // 1. callback_url: Server-to-server notification from Chapa
        // 2. return_url: User redirect after payment completion
        // Both scenarios send trx_ref parameter for verification
        
        $tx_ref = $request->get('trx_ref'); 

        if (!$tx_ref) {
            // Silent redirect if user is already pro (likely a page refresh)
            if (Auth::check() && Auth::user()->is_pro) {
                return redirect()->route('subscribe.index');
            }
            return redirect()->route('subscribe.index')
                ->with('error', 'Payment verification failed.');
        }

        // Verify with Chapa
        $response = Chapa::verifyTransaction($tx_ref);
        
        if ($response['status'] !== 'success') {
            return redirect()->route('subscribe.index')
                ->with('error', 'Payment verification failed.');
        }

        // Find subscription
        $subscription = Subscription::where('transaction_reference', $tx_ref)->first();
        
        if (!$subscription) {
            return redirect()->route('subscribe.index')
                ->with('error', 'Subscription not found.');
        }

        if ($subscription->status === 'active') {
            return redirect()->route('subscribe.index');
        }

        // Activate subscription
        $expiresAt = now()->addDays($subscription->duration_in_days);
        
        $subscription->update([
            'status' => 'active',
            'paid_at' => now(),
            'starts_at' => now(),
            'expires_at' => $expiresAt,
        ]);

        // Update user pro status
        $subscription->user->update([
            'is_pro' => true,
            'pro_expires_at' => $expiresAt,
        ]);

        return redirect()->route('subscribe.index')
            ->with('success', 'Subscription activated successfully! Welcome to Pro!');
    }
}
