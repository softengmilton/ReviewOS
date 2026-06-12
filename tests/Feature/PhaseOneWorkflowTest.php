<?php

namespace Tests\Feature;

use App\Models\Board;
use App\Models\Organization;
use App\Models\Post;
use App\Models\PostStatus;
use App\Models\User;
use App\Notifications\PostStatusChangedNotification;
use App\Support\CurrentOrganization;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PhaseOneWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_complete_phase_one_feedback_workflow(): void
    {
        Notification::fake();
        $this->seed(RoleSeeder::class);

        $this->post('/register', [
            'name' => 'Phase Owner',
            'email' => 'phase-owner@example.com',
            'organization_name' => 'Phase One Inc',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect('/dashboard');

        $this->post('/boards', [
            'name' => 'Feature Requests',
            'description' => 'Customer ideas',
            'type' => 'public',
        ])->assertRedirect();

        $board = Board::firstOrFail();

        $this->post("/boards/{$board->id}/posts", [
            'title' => 'Add saved views',
            'content' => 'Customers need reusable filters.',
        ])->assertRedirect();

        $post = Post::firstOrFail();

        $this->postJson("/posts/{$post->id}/vote", [
            'direction' => 'up',
        ])->assertOk()->assertJsonPath('upvotes_count', 1);

        $this->post("/posts/{$post->id}/comments", [
            'content' => 'This would help support teams.',
        ])->assertRedirect();

        $planned = PostStatus::where('type', 'planned')->firstOrFail();

        $this->put("/posts/{$post->id}", [
            'title' => $post->title,
            'content' => $post->content,
            'status_id' => $planned->id,
        ])->assertRedirect();

        $post->refresh();

        $this->assertSame(1, $post->upvotes_count);
        $this->assertSame(1, $post->comments_count);
        $this->assertSame($planned->id, $post->status_id);

        Notification::assertSentTo($this->app['auth']->user(), PostStatusChangedNotification::class);
    }

    public function test_existing_user_login_redirects_to_dashboard(): void
    {
        $this->seed(RoleSeeder::class);

        $user = User::factory()->create([
            'email' => 'login-owner@example.com',
            'password' => Hash::make('password123'),
        ]);

        $organization = Organization::create([
            'name' => 'Login Org',
            'slug' => 'login-org',
        ]);

        $organization->users()->attach($user->id, [
            'role' => 'owner',
            'joined_at' => now(),
        ]);

        $user->assignRole('owner');

        $this->post('/login', [
            'email' => 'login-owner@example.com',
            'password' => 'password123',
        ])->assertRedirect('/dashboard');

        $this->assertAuthenticatedAs($user);
    }

    public function test_tenant_scoped_routes_do_not_expose_other_organization_boards(): void
    {
        $this->seed(RoleSeeder::class);

        $firstUser = User::factory()->create(['email' => 'first@example.com']);
        $firstOrg = Organization::create(['name' => 'First Org', 'slug' => 'first-org']);
        $firstOrg->users()->attach($firstUser->id, ['role' => 'owner', 'joined_at' => now()]);

        $this->actingAs($firstUser);
        app(CurrentOrganization::class)->set($firstOrg);
        $firstBoard = Board::create(['name' => 'First Board', 'slug' => 'first-board']);

        $secondUser = User::factory()->create(['email' => 'second@example.com']);
        $secondOrg = Organization::create(['name' => 'Second Org', 'slug' => 'second-org']);
        $secondOrg->users()->attach($secondUser->id, ['role' => 'owner', 'joined_at' => now()]);

        $this->actingAs($secondUser)
            ->get("/boards/{$firstBoard->id}")
            ->assertNotFound();
    }

    public function test_billing_plan_can_be_changed_for_current_organization(): void
    {
        $this->seed(RoleSeeder::class);

        $this->post('/register', [
            'name' => 'Billing Owner',
            'email' => 'billing-owner@example.com',
            'organization_name' => 'Billing Inc',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect('/dashboard');

        $this->post('/settings/billing/checkout', ['plan' => 'starter'])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertSame('starter', $this->app['auth']->user()->organizations()->first()->plan);
    }
}
