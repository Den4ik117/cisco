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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->text('name');
            $table->string('image_content')->nullable();
            $table->string('type');
            $table->foreignId('marathon_id')->nullable()->constrained('marathons')->cascadeOnDelete();
            $table->foreignId('task_id')->nullable()->constrained('tasks')->cascadeOnDelete();
            $table->foreignId('module_id')->nullable()->constrained('modules')->cascadeOnDelete();
            $table->boolean('is_success')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
