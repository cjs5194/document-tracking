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
        Schema::create('document_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('document_id')->constrained()->onDelete('cascade');
        $table->string('changed_by'); // e.g., user name or role
        $table->string('type'); // "status", "records", etc.
        $table->string('status')->nullable(); // Under Review, In Progress, etc.
        $table->text('remarks')->nullable();
        $table->timestamps(); // stores when this log was created
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_logs');
    }
};
