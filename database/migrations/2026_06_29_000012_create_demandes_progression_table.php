<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demandes_progression', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membre_id')->constrained('users')->cascadeOnDelete();
            $table->string('from_level', 50);
            $table->string('to_level', 50);
            $table->string('formations_score', 50)->nullable();
            $table->string('assiduite_score', 50)->nullable();
            $table->string('anciennete', 50)->nullable();
            $table->string('service_sessions', 50)->nullable();
            $table->string('statut', 20)->default('en_attente');
            $table->text('motif_refus')->nullable();
            $table->foreignId('traite_par_id')->nullable()->constrained('users')->nullOnDelete();
            $table->datetime('date_traitement')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demandes_progression');
    }
};
