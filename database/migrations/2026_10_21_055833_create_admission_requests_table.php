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
        Schema::create('admission_requests', function (Blueprint $table) {
            $table->id();
            $table->string('student_name');
            $table->string('parent_name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('grade_level');
            $table->foreignId('major_id')->nullable()->constrained()->nullOnDelete();
            $table->text('note')->nullable();
            $table->string('status')->default('new');
            $table->timestamps();

            $table->index('status');
            $table->index(['grade_level', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_requests');
    }
};
