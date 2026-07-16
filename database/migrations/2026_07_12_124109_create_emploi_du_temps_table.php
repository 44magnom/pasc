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
Schema::create('emplois_du_temps', function (Blueprint $table) {
    $table->id();

    $table->foreignId('jour_id')
        ->constrained('jours')
        ->cascadeOnDelete();

    $table->foreignId('matiere_id')
        ->constrained('matieres')
        ->cascadeOnDelete();

    $table->text('commentaire')->nullable();

    $table->timestamps();

    // Évite de programmer deux fois la même matière le même jour
    $table->unique(['jour_id', 'matiere_id']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emploi_du_temps');
    }
};
