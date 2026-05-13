<?php

use App\Enums\ChallengeDifficulty;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /**
         * Challenges are unique and will have their content stored as entries in the
         */
        Schema::create('challenges', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title')->unique();
            $table->timestamps();
        });
        Schema::create('challenge_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();

        });
        Schema::create('challenge_versions', function (Blueprint $table) {
            $table->id();
            $table->integer('version');
            $table->foreignUuid('challenge_id')->references('id')->on('challenges');
            // Challenge versions should be unique to each challenge
            $table->unique(['version', 'challenge_id']);
            $table->text('description_md');
            $table->string('difficulty')->default(ChallengeDifficulty::Easy->value);
            $table->foreignId('challenge_category_id')->references('id')->on('challenge_categories');
            $table->integer('points');
            $table->string('flag');
            $table->unsignedInteger('max_attempts')->default(0);
            // Laravel expects this to be user_id, but in this case author_id is more descriptive.
            $table->foreignId('author_id')->references('id')->on('users');
            $table->text('tags')->nullable();
            $table->integer('est_time_to_solve')->nullable();
            $table->text('solution_md');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenge_versions');
        Schema::dropIfExists('challenges');
        Schema::dropIfExists('challenge_categories');
    }
};
