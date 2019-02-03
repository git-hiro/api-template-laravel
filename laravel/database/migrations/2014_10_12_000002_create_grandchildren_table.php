<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGrandChildrenTable extends Migration
{
  /**
   * Run the migrations.
   */
  public function up()
  {
    Schema::create('grandchildren', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->uuid('child_id');

      $table->uuid('creator_id');
      $table->uuid('updater_id');
      $table->timestamps();
      $table->softDeletes();

      $table->foreign('child_id')->references('id')->on('children')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down()
  {
    Schema::dropIfExists('grandchildren');
  }
}
