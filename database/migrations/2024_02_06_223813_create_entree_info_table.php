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
        Schema::create('entree_info', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fournisseur_id');
            $table->string('numero_facture');
            $table->string('transporteur')->nullable();
            $table->string('plaque_immat')->nullable();
            $table->text('commentaire')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entree_info');
    }
};
