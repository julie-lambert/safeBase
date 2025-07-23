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
         Schema::create('backups', function (Blueprint $table) {
        $table->id();
        $table->foreignId('db_connection_id')->constrained('database_connections')->onDelete('cascade');
        $table->string('file_path');
        $table->enum('status', ['success', 'fail'])->default('success');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backups');
    }
};
