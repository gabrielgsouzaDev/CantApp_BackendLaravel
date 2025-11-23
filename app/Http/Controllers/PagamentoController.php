<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PagamentoController extends Controller
{
    public function payWithPix(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $paymentIntent = PaymentIntent::create([
            'amount' => $request->amount,
            'currency' => 'brl',
            'payment_method_types' => ['pix'],
        ]);

        return response()->json([
            'client_secret' => $paymentIntent->client_secret,
            'qr_code' => $paymentIntent->next_action['pix_display_qr_code']['data'],
        ]);
    }

    public function payWithCard(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $paymentIntent = PaymentIntent::create([
            'amount' => $request->amount,
            'currency' => 'brl',
            'payment_method_types' => ['card'],
        ]);

        return response()->json([
            'client_secret' => $paymentIntent->client_secret,
        ]);
    }

    public function webhook(Request $request)
    {
        $payload = @file_get_contents('php://input');
        $event = json_decode($payload);

        if (!$event) {
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        if ($event->type === 'payment_intent.succeeded') {
            $paymentIntent = $event->data->object;

            // Exemplo: atualizar pagamento no banco
            // Payment::where('payment_intent', $paymentIntent->id)
            //     ->update(['status' => 'paid']);
        }

        return response()->json(['status' => 'ok']);
    }
}
