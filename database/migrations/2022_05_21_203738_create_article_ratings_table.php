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
        Schema::create('article_ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('article_id');
            $table->ipAddress();
            $table->unsignedTinyInteger('rating');
            $table->timestamps();

            $table->unique(['article_id', 'ip_address']);
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_ratings');
    }
};
