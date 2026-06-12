<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use App\Support\CurrentOrganization;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveOrganization
{
    public function handle(Request $request, Closure $next): Response
    {
        $organization = null;

        if ($request->user()) {
            $organization = $request->user()->organizations()->first();
        }

        if (! $organization && $slug = $request->route('organization')) {
            $organization = Organization::where('slug', $slug)->first();
        }

        app(CurrentOrganization::class)->set($organization);

        return $next($request);
    }
}
