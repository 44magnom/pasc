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
Schema::create('forfaits', function (Blueprint $table) {
    $table->id();

    // Nom du forfait
    $table->string('nom');

    // Durée en jours (30, 90, 365...)
    $table->integer('duree');

    // Prix en FCFA
    $table->integer('montant');

    // Description
    $table->text('description')->nullable();

    // Actif ou non
    $table->boolean('is_active')->default(true);

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forfaits');
    }
};
