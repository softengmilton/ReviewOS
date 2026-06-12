<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StripeWebhookController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        logger()->info('Stripe webhook received', [
            'type' => $request->input('type'),
        ]);

        return response()->json(['received' => true]);
    }
}
