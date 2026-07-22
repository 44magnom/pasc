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
Schema::create('notes', function (Blueprint $table) {
    $table->id();
        $table->boolean('is_revised')->default(true);

    $table->foreignId('chapitre_id')
          ->constrained()
          ->cascadeOnDelete();

    $table->text('recto');
    $table->text('verso');

    // Nombre de révisions
    $table->unsignedInteger('nombre_revision')->default(0);

    // Date de la prochaine révision
    $table->date('prochaine_revision');

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
