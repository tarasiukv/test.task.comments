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
        Schema::create('comment_files', function (Blueprint $table) {
            $table->id();
            $table->string('file_path')->nullable();
            $table->foreignId('comment_id')->nullable()
                ->constrained('comments')
                ->onUpdate('cascade')
                ->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comment_files');
    }
};
