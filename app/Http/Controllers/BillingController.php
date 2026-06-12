<?php

namespace App\Http\Controllers;

use App\Support\CurrentOrganization;
use App\Http\Requests\BillingCheckoutRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class BillingController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Billing/Index', [
            'plans' => [
                ['key' => 'free', 'name' => 'Free', 'price' => 0],
                ['key' => 'starter', 'name' => 'Starter', 'price' => 29],
                ['key' => 'growth', 'name' => 'Growth', 'price' => 99],
                ['key' => 'business', 'name' => 'Business', 'price' => 249],
            ],
            'currentPlan' => app(CurrentOrganization::class)->get()?->plan ?? 'free',
        ]);
    }

    public function checkout(BillingCheckoutRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $organization = app(CurrentOrganization::class)->get();
        $organization?->update(['plan' => $validated['plan']]);

        return back()->with('success', 'Plan updated. Configure Stripe price IDs to enable hosted checkout.');
    }
}
