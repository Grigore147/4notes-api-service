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
        Schema::create('notebooks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('space_id')->nullable()->index();
            $table->uuid('stack_id')->nullable()->index();

            $table->uuid('user_id')->index();

            $table->string('name', 128)->default('');

            $table->timestamps();

            $table->foreign('space_id')
                ->references('id')
                ->on('spaces')
                ->onDelete('set null');

            $table->foreign('stack_id')
                ->references('id')
                ->on('stacks')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notebooks');
    }
};
