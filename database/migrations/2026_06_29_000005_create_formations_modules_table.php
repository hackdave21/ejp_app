<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('formations_modules', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('categorie', 50);
            $table->string('icone', 100)->nullable();
            $table->integer('ordre')->default(0);
            $table->text('description')->nullable();
            $table->string('video_url')->nullable();
            $table->string('duree', 50)->nullable();
            $table->boolean('statut')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formations_modules');
    }
};
