<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public static array $defaults = [
        ['name' => 'professor', 'label' => 'Professor'],
        ['name' => 'colaborador', 'label' => 'Colaborador'],
        ['name' => 'coordenador', 'label' => 'Coordenador'],
    ];

    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();        // ex: articles.create
            $table->string('label')->nullable();     // ex: Criar artigos
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
