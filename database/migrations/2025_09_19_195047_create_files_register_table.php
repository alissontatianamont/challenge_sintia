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
        Schema::create('files_register', function (Blueprint $table) {
            $table->id();
            $table->string('file_name')->required();
            $table->string('file_type')->required();
            $table->bigInteger('file_size')->required();
            $table->foreignId('file_user_id')->constrained('users')->onDelete('cascade')->required();
            $table->string('file_google_id')->required();
            $table->timestamp('file_created')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files_register');
    }
};
