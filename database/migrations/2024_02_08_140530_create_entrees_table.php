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
        Schema::create('entrees', function (Blueprint $table) {
            $table->id();
            $table->string('entree_info_id');
            $table->unsignedBigInteger('produit_id');
            $table->integer('quantite');
            $table->decimal('prix_unitaire', 8, 2)->nullable();
            $table->string('emplacement_stockage')->nullable();
            $table->string('commentaire')->nullable();
            $table->date('date_peremption')->nullable();
            $table->string('palette')->nullable();
            $table->timestamps();

            $table->foreign('produit_id')->references('id')->on('produits');
            $table->foreign('entree_info_id')->references('numero_facture')->on('entree_info');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entrees');
    }
};
