<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use App\Support\CurrentOrganization;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'organization_name' => 'required|string|max:255',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        [$user, $organization] = DB::transaction(function () use ($request): array {
            $organizationName = (string) $request->string('organization_name');

            $organization = Organization::create([
                'name' => $organizationName,
                'slug' => Str::slug($organizationName).'-'.Str::lower(Str::random(6)),
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $organization->users()->attach($user->id, [
                'role' => 'owner',
                'joined_at' => now(),
            ]);

            if (method_exists($user, 'assignRole') && Role::where('name', 'owner')->exists()) {
                $user->assignRole('owner');
            }

            return [$user, $organization];
        });

        event(new Registered($user));

        Auth::login($user);
        app(CurrentOrganization::class)->set($organization);

        return redirect(route('dashboard', absolute: false));
    }
}
