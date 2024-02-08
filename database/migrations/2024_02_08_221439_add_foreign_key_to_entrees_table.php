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
        Schema::table('entrees', function (Blueprint $table) {
            $table->unsignedBigInteger('emplacement_stockage')->nullable()->change();
            $table->foreign('emplacement_stockage')->references('id')->on('racks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entrees', function (Blueprint $table) {
            $table->string('emplacement_stockage')->nullable()->change();
            $table->dropForeign(['emplacement_stockage']);
        });
    }
};
