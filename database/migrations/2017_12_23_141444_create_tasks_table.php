<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('name');
            $table->string('slug');
            $table->integer('category_id')->unsigned()->nullable();
            $table->text('description');
            $table->text('answer');
            $table->float('salary');
            $table->string('url')->nullable();
            $table->integer('technology');
            $table->integer('period')->nullable();
            $table->integer('time');
            $table->integer('type');
            $table->text('question')->nullable();
            $table->integer('success')->default(0);
            $table->integer('danger')->default(0);
            $table->integer('available')->default(0);
            $table->boolean('is_show')->default(false);
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
        Schema::dropIfExists('tasks');
    }
}
