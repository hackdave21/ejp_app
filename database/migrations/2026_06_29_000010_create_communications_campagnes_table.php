<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('communications_campagnes', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('canal', 50);
            $table->string('audience_cible', 50);
            $table->text('contenu');
            $table->datetime('date_envoi')->nullable();
            $table->string('statut', 20)->default('brouillon');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('communications_campagnes');
    }
};
