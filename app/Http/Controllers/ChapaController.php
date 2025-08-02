<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Chapa\Chapa\Facades\Chapa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ChapaController extends Controller
{
    protected $chapa;

    public function __construct()
    {
        $this->chapa = Chapa::class;
    }

    public function initialize(Request $request)
    {
        $user = Auth::user();
        $tx_ref = 'TX-' . Str::uuid();

        $amount = 100;
        $duration = 30;

        Subscription::create([
            'user_id' => $user->id,
            'payment_method' => 'chapa',
            'status' => 'pending',
            'amount' => $amount,
            'duration_in_days' => $duration,
            'transaction_reference' => $tx_ref,
        ]);

        $data = [
            'amount' => $amount,
            'email' => $user->email,
            'first_name' => $user->name,
            'tx_ref' => $tx_ref,
            'currency' => 'ETB',
            'callback_url' => route('chapa.verify'),
            'return_url' => route('chapa.verify'),
            'customization' => [
                'title' => 'SSTutor Subscription',
                'description' => 'Subscribe to SSTutor to access premium content',
            ]
        ];
        $response = $this->chapa::initialize($data);

        if ($response['status'] === 'success') {
            return redirect($response['data']['checkout_url']);
        }
        return back()->with('error', 'Payment initialization failed');
    }

    public function verify(Request $request)
    {
        $tx_ref = $request->get('tx_ref');

        $response = $this->chapa::verify($tx_ref);
        if (!isset($response['status']) || $response['status'] !== 'success') {
            return redirect()->route('home')->with('error', 'Payment verification failed');
        }

        $data = $response['data'];
        $subscription = Subscription::where('transaction_reference', $tx_ref)->first();

        $subscription->update([
            'status' => 'active',
            'paid_at' => now(),
            'expires_at' => now()->addDays($subscription->duration_in_days),
        ]);

        return redirect()->route('home')->with('success', 'Subscription activated successfully');
    }

}
