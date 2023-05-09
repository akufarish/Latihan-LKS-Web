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
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("society_id");
            $table->unsignedBigInteger("doctor_id");
            $table->enum("status", [
                "accepted",
                "declined",
                "pending"
            ]);
            $table->string("disease_history");
            $table->string("current_symptoms");
            $table->string("doctor_notes");
            $table->timestamps();

            $table->foreign("society_id")->references("id")->on("societies");
            $table->foreign("doctor_id")->references("id")->on("medicals");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
