<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Spatie\Permission\Models\Role;

class SocialiteController extends Controller
{
    public function redirect(string $provider): RedirectResponse
    {
        abort_unless(in_array($provider, ['google', 'github'], true), 404);

        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider): RedirectResponse
    {
        abort_unless(in_array($provider, ['google', 'github'], true), 404);

        $oauthUser = Socialite::driver($provider)->user();

        $user = User::firstOrCreate(
            ['email' => $oauthUser->getEmail()],
            [
                'name' => $oauthUser->getName() ?: $oauthUser->getNickname() ?: 'FeedbackOS User',
                'avatar_url' => $oauthUser->getAvatar(),
                'auth_provider' => $provider,
                'auth_provider_id' => $oauthUser->getId(),
            ]
        );

        if (! $user->organizations()->exists()) {
            $organization = Organization::create([
                'name' => "{$user->name}'s Organization",
                'slug' => Str::slug($user->name).'-'.Str::lower(Str::random(6)),
            ]);
            $organization->users()->attach($user->id, ['role' => 'owner', 'joined_at' => now()]);
            Role::findOrCreate('owner', 'web');
            $user->assignRole('owner');
        }

        Auth::login($user, true);

        return redirect()->route('dashboard');
    }
}
