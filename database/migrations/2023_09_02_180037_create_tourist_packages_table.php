<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tourist_packages', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger("tourist_id");
            $table->foreign('tourist_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger("package_id");
            $table->foreign('package_id')->references('id')->on('tour_packages')->onDelete('cascade');

            $table->bigInteger("created_by")->dafault(0);
            $table->bigInteger("updated_by")->dafault(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tourist_packages');
    }
};
