<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('users_tasks', function (Blueprint $table) {

          $table->increments('id');
          $table->integer('user_id')->unsigned();
          $table->integer('task_id')->unsigned();
          $table->timestamps();
          $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
          $table->foreign('task_id')
                      ->references('id')->on('tasks')
                      ->onDelete('cascade');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('users_tasks');
    }
}
