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
        Schema::table('marathons', function (Blueprint $table) {
            $table->foreignId('last_task_id')->nullable()->constrained('tasks')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marathons', function (Blueprint $table) {
            $table->dropForeign(['last_task_id']);
            $table->dropColumn('last_task_id');
        });
    }
};
