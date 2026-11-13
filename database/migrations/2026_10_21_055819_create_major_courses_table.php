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
        Schema::create('major_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('major_id')->constrained()->cascadeOnDelete();
            $table->string('grade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('weekly_hours')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();

            $table->index(['major_id', 'grade']);
            $table->index(['grade', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('major_courses');
    }
};
