<?php

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
        Schema::create('skill_test_configs', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('50-Question Master Skill Test');
            $table->text('instructions')->nullable();
            $table->integer('duration_minutes')->default(50);
            $table->integer('total_questions')->default(50);
            $table->integer('pass_percentage')->default(60);
            $table->string('topic_mode')->default('all'); // 'all' or 'fixed'
            $table->json('fixed_topic_ids')->nullable(); // array of topic IDs when topic_mode is 'fixed'
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skill_test_configs');
    }
};
