<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // Here you can handle the Stripe webhook payload.
        // ...

        return response()->json(['status' => 'success']);
    }
}