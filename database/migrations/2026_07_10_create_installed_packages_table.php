<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('installed_packages', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // module|plugin|theme
            $table->string('slug');
            $table->string('version')->nullable();
            $table->string('path')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->unique(['type', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('installed_packages');
    }
};
