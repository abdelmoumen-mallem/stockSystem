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
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->nullable();
            $table->string('nom_produit');
            $table->text('description')->nullable();
            $table->string('categorie')->nullable();
            $table->decimal('prix_achat', 10, 2)->default(0);
            $table->decimal('prix_vente', 10, 2)->default(0);
            $table->integer('quantite_min_stock')->default(0);
            $table->integer('quantite_stock')->default(0);
            $table->unsignedBigInteger('fournisseur_id');
            $table->string('image_url')->nullable();
            $table->string('code_barres')->nullable();
            $table->decimal('taxes', 5, 2)->default(0);
            $table->decimal('remises', 5, 2)->default(0);
            $table->integer('status')->default(1);
            $table->integer('e_commerce')->default(1);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('fournisseur_id')->references('id')->on('fournisseurs');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
