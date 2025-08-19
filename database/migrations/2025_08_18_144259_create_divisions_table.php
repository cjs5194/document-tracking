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
        Schema::create('divisions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., OED, AFMD, etc.
            $table->timestamps();
        });

        // Add division_id to users table
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('division_id')
                  ->nullable()
                  ->constrained('divisions')
                  ->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['division_id']);
            $table->dropColumn('division_id');
        });

        Schema::dropIfExists('divisions');
    }
};
