<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('path');
            $table->string('mime');
             // Ensure the journal_entry_id foreign key is properly set up for cascading on delete
             $table->foreignId('journal_entry_id')->constrained('journal_entries')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes(); // Adds 'deleted_at' column
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
