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
        Schema::create('vaccinations', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger("dose");
            $table->date("date");
            $table->unsignedBigInteger("society_id");
            $table->unsignedBigInteger("spot_id");
            $table->unsignedBigInteger("vaccine_id");
            $table->unsignedBigInteger("doctor_id");
            $table->unsignedBigInteger("office_id");
            $table->timestamps();

            $table->foreign("society_id")->references("id")->on("societies");
            $table->foreign("spot_id")->references("id")->on("spots");
            $table->foreign("vaccine_id")->references("id")->on("vaccines");
            $table->foreign("doctor_id")->references("id")->on("medicals");
            $table->foreign("office_id")->references("id")->on("medicals");

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vaccinations');
    }
};
