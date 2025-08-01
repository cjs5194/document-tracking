<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->timestamp('under_review_at')->nullable()->after('oed_date_received');
            $table->timestamp('in_progress_at')->nullable()->after('under_review_at');
            $table->timestamp('for_release_at')->nullable()->after('in_progress_at');
        });
    }

    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn(['under_review_at', 'in_progress_at', 'for_release_at']);
        });
    }
};
