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
        Schema::create('halisaha', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->integer('userId');
            $table->string('starthour');
            $table->string('endhour');
            $table->string('offdays');
            $table->string('macsuresi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('halisaha');
    }
};
