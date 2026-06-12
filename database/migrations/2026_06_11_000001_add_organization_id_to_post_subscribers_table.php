<?php

use App\Models\Post;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('post_subscribers', 'organization_id')) {
            return;
        }

        Schema::table('post_subscribers', function (Blueprint $table) {
            $table->foreignUuid('organization_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();
        });

        Post::withoutGlobalScopes()
            ->select(['id', 'organization_id'])
            ->chunkById(100, function ($posts): void {
                foreach ($posts as $post) {
                    Schema::getConnection()
                        ->table('post_subscribers')
                        ->where('post_id', $post->id)
                        ->whereNull('organization_id')
                        ->update(['organization_id' => $post->organization_id]);
                }
            });

        Schema::table('post_subscribers', function (Blueprint $table) {
            $table->index('organization_id');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('post_subscribers', 'organization_id')) {
            return;
        }

        Schema::table('post_subscribers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('organization_id');
        });
    }
};
