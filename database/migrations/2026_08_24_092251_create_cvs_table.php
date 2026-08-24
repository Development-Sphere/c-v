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
        Schema::create('cvs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('session_id')->nullable()->index();
            $table->string('template')->default('modern');
            $table->string('title')->nullable();
            $table->json('personal_info')->default(new \Illuminate\Database\Query\Expression("('{}')"));
            $table->json('summary')->default(new \Illuminate\Database\Query\Expression("('{\"raw\":\"\",\"polished\":\"\"}')"));
            $table->json('experience')->default(new \Illuminate\Database\Query\Expression("('[]')"));
            $table->json('education')->default(new \Illuminate\Database\Query\Expression("('[]')"));
            $table->json('skills')->default(new \Illuminate\Database\Query\Expression("('[]')"));
            $table->json('extras')->nullable();
            $table->enum('status', ['draft', 'complete'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cvs');
    }
};
