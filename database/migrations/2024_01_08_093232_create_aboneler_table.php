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
        Schema::create('aboneler', function (Blueprint $table) {
            $table->id();
            $table->string("startdate");
            $table->string("enddate");
            $table->integer("sahaId");
            $table->string("userName")->nullable();
            $table->string("userinfo")->nullable();
            $table->string("note")->nullable();

      


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aboneler');
    }
};
