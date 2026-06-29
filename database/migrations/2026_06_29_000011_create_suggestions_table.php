<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suggestions', function (Blueprint $table) {
            $table->id();
            $table->string('categorie', 50);
            $table->string('nom', 100)->nullable();
            $table->text('contenu');
            $table->string('statut', 20)->default('nouveau');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('lu_par_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suggestions');
    }
};
