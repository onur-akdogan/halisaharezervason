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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->integer("sahaId");
            $table->string("title");
            $table->string("date");
            $table->string("userName")->nullable();
            $table->string("userinfo")->nullable();
            $table->string("note")->nullable();
            
            $table->integer("deleted")->default(0);
            $table->integer("smsstatus")->default(0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
