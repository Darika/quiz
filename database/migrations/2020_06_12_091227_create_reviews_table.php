<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->increments('id');
			$table->bigInteger('user_id')->unsigned()->index()->nullable();
        	$table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        	$table->text('review');
        	$table->bigInteger('like')->default(0)->nullable();
        	$table->bigInteger('dislike')->default(0)->nullable();
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
        Schema::dropIfExists('reviews');
    }
}
