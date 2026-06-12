<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->constrained()->cascadeOnDelete();
            $table->string('email')->nullable();
            $table->string('name')->nullable();
            $table->string('external_id')->nullable();
            $table->string('avatar_url')->nullable();
            $table->json('metadata')->nullable();
            $table->json('segment_ids')->nullable();
            $table->timestamps();
            $table->unique(['organization_id', 'email']);
            $table->unique(['organization_id', 'external_id']);
            $table->index('organization_id');
        });

        Schema::create('custom_domains', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('organization_id')->constrained()->cascadeOnDelete();
            $table->string('domain')->unique();
            $table->string('module');
            $table->timestamp('verified_at')->nullable();
            $table->string('ssl_status')->default('pending');
            $table->string('verification_token')->nullable();
            $table->timestamps();
        });

        Schema::create('boards', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('type')->default('public');
            $table->json('settings')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->unique(['organization_id', 'slug']);
            $table->index('organization_id');
        });

        Schema::create('post_statuses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('color')->default('#6B7280');
            $table->string('type');
            $table->integer('sort_order')->default(0);
            $table->boolean('notify_subscribers')->default(true);
            $table->timestamps();
            $table->index('organization_id');
        });

        Schema::create('post_tags', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('board_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('color')->default('#6B7280');
            $table->boolean('is_internal')->default(false);
            $table->timestamps();
            $table->index('organization_id');
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('board_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('organization_id')->constrained()->cascadeOnDelete();
            $table->string('author_type');
            $table->uuid('author_id');
            $table->foreignUuid('status_id')->nullable()->constrained('post_statuses')->nullOnDelete();
            $table->string('title');
            $table->text('content')->nullable();
            $table->string('sentiment')->nullable();
            $table->integer('sentiment_score')->nullable();
            $table->integer('upvotes_count')->default(0);
            $table->integer('downvotes_count')->default(0);
            $table->integer('comments_count')->default(0);
            $table->float('priority_score')->default(0);
            $table->boolean('is_internal')->default(false);
            $table->boolean('is_hidden')->default(false);
            $table->boolean('is_pinned')->default(false);
            $table->date('eta')->nullable();
            $table->foreignUuid('merged_into_id')->nullable()->constrained('posts')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index('organization_id');
            $table->index('board_id');
            $table->index('status_id');
            $table->index(['author_type', 'author_id']);
        });

        Schema::create('post_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('post_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('organization_id')->constrained()->cascadeOnDelete();
            $table->string('voter_type');
            $table->uuid('voter_id');
            $table->string('direction')->default('up');
            $table->foreignUuid('on_behalf_of_id')->nullable()->constrained('customer_users')->nullOnDelete();
            $table->unique(['post_id', 'voter_type', 'voter_id']);
            $table->timestamps();
        });

        Schema::create('post_comments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('post_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('organization_id')->constrained()->cascadeOnDelete();
            $table->string('author_type');
            $table->uuid('author_id');
            $table->text('content');
            $table->boolean('is_internal')->default(false);
            $table->boolean('is_pinned')->default(false);
            $table->foreignUuid('parent_id')->nullable()->constrained('post_comments')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('post_tag', function (Blueprint $table) {
            $table->foreignUuid('post_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('post_tag_id')->constrained('post_tags')->cascadeOnDelete();
            $table->primary(['post_id', 'post_tag_id']);
        });

        Schema::create('post_merges', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('parent_post_id')->constrained('posts')->cascadeOnDelete();
            $table->foreignUuid('child_post_id')->constrained('posts')->cascadeOnDelete();
            $table->foreignUuid('merged_by_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('merged_at');
            $table->timestamps();
        });

        Schema::create('post_subscribers', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('post_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('organization_id')->constrained()->cascadeOnDelete();
            $table->string('subscriber_type');
            $table->uuid('subscriber_id');
            $table->boolean('notify_status_change')->default(true);
            $table->boolean('notify_comment')->default(false);
            $table->unique(['post_id', 'subscriber_type', 'subscriber_id']);
            $table->index('organization_id');
            $table->timestamps();
        });

        Schema::create('post_assignees', function (Blueprint $table) {
            $table->foreignUuid('post_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->primary(['post_id', 'user_id']);
        });

        try {
            DB::statement('CREATE TABLE post_embeddings (
                id BIGSERIAL PRIMARY KEY,
                post_id UUID NOT NULL REFERENCES posts(id) ON DELETE CASCADE,
                organization_id UUID NOT NULL REFERENCES organizations(id) ON DELETE CASCADE,
                embedding vector(1536),
                created_at TIMESTAMP DEFAULT NOW(),
                updated_at TIMESTAMP DEFAULT NOW()
            )');
            DB::statement('CREATE INDEX post_embeddings_embedding_idx ON post_embeddings USING ivfflat (embedding vector_cosine_ops) WITH (lists = 100)');
        } catch (Throwable) {
            Schema::create('post_embeddings', function (Blueprint $table) {
                $table->id();
                $table->foreignUuid('post_id')->constrained()->cascadeOnDelete();
                $table->foreignUuid('organization_id')->constrained()->cascadeOnDelete();
                $table->json('embedding')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('post_embeddings');
        Schema::dropIfExists('post_assignees');
        Schema::dropIfExists('post_subscribers');
        Schema::dropIfExists('post_merges');
        Schema::dropIfExists('post_tag');
        Schema::dropIfExists('post_comments');
        Schema::dropIfExists('post_votes');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('post_tags');
        Schema::dropIfExists('post_statuses');
        Schema::dropIfExists('boards');
        Schema::dropIfExists('custom_domains');
        Schema::dropIfExists('customer_users');
    }
};
