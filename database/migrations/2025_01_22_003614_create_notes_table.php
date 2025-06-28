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
        Schema::create('notes', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('user_id')->index();
            $table->uuid('notebook_id')->nullable()->index();

            $table->string('title', 255)->default('');
            $table->text('content');

            $table->timestamps();

            $table->foreign('notebook_id')
                ->references('id')
                ->on('notebooks')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
