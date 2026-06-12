<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use App\Services\DefaultOrganizationSetup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultOrganizationSeeder extends Seeder
{
    public function run(): void
    {
        $organization = Organization::firstOrCreate(
            ['slug' => 'feedbackos-demo'],
            ['name' => 'FeedbackOS Demo', 'plan' => 'free']
        );

        $user = User::firstOrCreate(
            ['email' => 'owner@example.com'],
            ['name' => 'Demo Owner', 'password' => Hash::make('password')]
        );

        $organization->users()->syncWithoutDetaching([
            $user->id => ['role' => 'owner', 'joined_at' => now()],
        ]);

        app(DefaultOrganizationSetup::class)->seed($organization);
    }
}
