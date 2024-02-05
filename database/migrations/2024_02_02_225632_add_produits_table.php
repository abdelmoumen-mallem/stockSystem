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
        Schema::table('produits', function (Blueprint $table) {
            $table->string('image_url_2')->nullable();
            $table->string('image_url_3')->nullable();
            $table->string('image_url_4')->nullable();
            $table->string('image_url_5')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->dropColumn(['image_url_2', 'image_url_3', 'image_url_4', 'image_url_5']);
        });
    }
};

