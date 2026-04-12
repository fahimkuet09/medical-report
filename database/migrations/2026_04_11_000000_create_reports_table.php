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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('cardio_id')->unique();
            $table->string('patient_name');
            $table->string('age');
            $table->string('sex');
            $table->string('patient_id');
            $table->string('referred_by')->nullable();
            $table->string('specimen')->nullable();
            $table->date('date_received')->nullable();
            $table->date('date_delivery')->nullable();
            $table->string('ward_cabin')->nullable();
            $table->string('bed')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
