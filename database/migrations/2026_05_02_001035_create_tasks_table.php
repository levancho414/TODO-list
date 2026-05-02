<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates the `tasks` table that backs the Task Manager.
 *
 * Schema:
 *   id          auto-increment primary key
 *   title       required, up to 255 chars
 *   description optional free-text
 *   status      "pending" or "done" (defaults to pending)
 *   deadline    optional date (no time component needed)
 *   timestamps  created_at / updated_at
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status')->default('pending');
            $table->date('deadline')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
