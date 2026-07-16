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
Schema::create('validation_emplois', function (Blueprint $table) {
    $table->id();

    $table->foreignId('emploi_du_temps_id')
        ->constrained('emplois_du_temps')
        ->cascadeOnDelete();

    $table->date('debut_semaine');

    $table->timestamps();

    $table->unique([
        'emploi_du_temps_id',
        'debut_semaine'
    ]);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('validation_emplois');
    }
};
