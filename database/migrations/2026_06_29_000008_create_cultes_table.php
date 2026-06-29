<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cultes', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('type', 100);
            $table->string('theme');
            $table->string('orateur');
            $table->integer('hommes')->default(0);
            $table->integer('femmes')->default(0);
            $table->integer('enfants')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cultes');
    }
};
