<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('motivational_messages', function (Blueprint $table) {
            $table->id();
            $table->string('score_level');
            $table->string('image_url')->nullable();
            $table->string('video_url')->nullable();
            $table->text('message');
            $table->boolean('is_displayed');
            $table->timestamps();


        });
    }

    public function down(): void
    {
        Schema::dropIfExists('motivational_messages');
    }
};