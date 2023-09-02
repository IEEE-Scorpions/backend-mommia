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
        Schema::create('pharaohs', function (Blueprint $table) {
            $table->id();
            $table->longText('name')->nullable();
            $table->longText('name_ar')->nullable();

            $table->longText('slug')->nullable();
            $table->longText('description')->nullable();

            $table->integer("active")->default(1);
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
        Schema::dropIfExists('pharaohs');
    }
};
