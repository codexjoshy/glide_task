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
        Schema::create('oui_data', function (Blueprint $table) {
            $table->id();
            $table->string('registry')->nullable();
            $table->string('assignment')->nullable();
            $table->string('organisation_name')->nullable();
            $table->string('organisation_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oui_data');
    }
};
