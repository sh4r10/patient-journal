<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop the table if it exists to avoid conflicts
        Schema::dropIfExists('patient_treatment');

        Schema::create('treatments', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description');
            $table->timestamps();
            $table->softDeletes(); 
            $table->string('deleted_by')->nullable()->after('deleted_at');
        });

        Schema::create('patient_treatment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('treatment_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes(); 
            $table->string('deleted_by')->nullable()->after('deleted_at');
        });
    }

    public function down(): void
    {
        // Drop the table first
        Schema::dropIfExists('patient_treatment', function (Blueprint $table) {
            $table->dropColumn('deleted_by');
        });
        Schema::dropIfExists('treatments');
    }
};
