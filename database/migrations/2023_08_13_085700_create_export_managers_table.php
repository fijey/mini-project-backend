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
        Schema::create('export_managers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('from_page');
            $table->timestamp('export_start')->nullable();
            $table->timestamp('export_end')->nullable();
            $table->string('status');
            $table->string('url_file');
            $table->bigInteger('user_id');
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
        Schema::dropIfExists('export_managers');
    }
};
