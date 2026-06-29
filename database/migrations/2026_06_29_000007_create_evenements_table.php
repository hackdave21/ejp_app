<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evenements', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('type', 50);
            $table->integer('capacite_max')->default(0);
            $table->datetime('date_debut');
            $table->datetime('date_fin');
            $table->string('lieu');
            $table->text('description')->nullable();
            $table->string('image_couverture')->nullable();
            $table->integer('nombre_participants')->default(0);
            $table->string('rapport')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evenements');
    }
};
