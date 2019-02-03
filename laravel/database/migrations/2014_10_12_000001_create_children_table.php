<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChildrenTable extends Migration
{
  /**
   * Run the migrations.
   */
  public function up()
  {
    Schema::create('children', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->uuid('user_id');

      $table->uuid('creator_id');
      $table->uuid('updater_id');
      $table->timestamps();
      $table->softDeletes();

      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down()
  {
    Schema::dropIfExists('children');
  }
}
