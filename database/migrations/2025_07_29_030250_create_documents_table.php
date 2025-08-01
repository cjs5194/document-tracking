<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->date('date_received');
            $table->string('document_no');
            $table->string('document_type');
            $table->string('particulars');
            $table->string('oed_received')->nullable();
            $table->date('oed_date_received')->nullable();
            $table->string('oed_status')->nullable();
            $table->string('oed_remarks')->nullable();
            $table->string('records_received')->nullable();
            $table->date('records_date_received')->nullable();
            $table->string('records_remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('documents');
    }
};

