<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTables extends Migration
{
  /**
   * Run the migrations.
   */
  public function up()
  {
    Schema::create('users', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->string('name');
      $table->string('email')->unique();
      $table->string('password');

      $table->uuid('creator_id');
      $table->uuid('updater_id');
      $table->timestamps();
      $table->softDeletes();
    });

    Schema::create('articles', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->uuid('user_id');
      $table->string('subject');
      $table->string('content');

      $table->uuid('creator_id');
      $table->uuid('updater_id');
      $table->timestamps();
      $table->softDeletes();

      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });

    Schema::create('comments', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->uuid('user_id');
      $table->uuid('article_id');
      $table->string('content');

      $table->uuid('creator_id');
      $table->uuid('updater_id');
      $table->timestamps();
      $table->softDeletes();

      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
    });

    Schema::create('tokens', function (Blueprint $table) {
      $table->uuid('value')->primary();
      $table->uuid('user_id');

      $table->timestamps();

      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down()
  {
    Schema::dropIfExists('tokens');
    Schema::dropIfExists('comments');
    Schema::dropIfExists('articles');
    Schema::dropIfExists('users');
  }
}
