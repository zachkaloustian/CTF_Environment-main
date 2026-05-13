<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('challenge_solves', function (Blueprint $table) {
            $table->id();

            $table->uuid('user_id')->constrained('users')->onDelete('cascade');
            $table->uuid('challenge_id');

            // store title + version points so it’s permanently saved even if updated later
            $table->string('challenge_title');
            $table->integer('points');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('challenge_solves');
    }
};
