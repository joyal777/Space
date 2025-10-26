<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('task_code')->unique();
            $table->string('task_name');
            $table->text('task_description')->nullable();
            $table->enum('task_status', ['pending', 'in_progress', 'completed', 'on_hold', 'cancelled'])->default('pending');
            $table->integer('priority')->default(1); // 1-5, 1 being highest
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('completed_at')->nullable();
            $table->integer('estimated_hours')->nullable();
            $table->integer('actual_hours')->nullable();
            $table->json('assigned_to')->nullable(); // For storing assigned users
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['project_id', 'task_status']);
            $table->index('priority');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
