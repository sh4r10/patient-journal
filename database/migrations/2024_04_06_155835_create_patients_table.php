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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('personnummer')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->timestamps();
            $table->softDeletes();      //  Adds 'deleted_at' column
            $table->string('deleted_by')->nullable()->after('deleted_at');
        });
       
    }

    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    { 
        Schema::dropIfExists('patients');
    }
};
