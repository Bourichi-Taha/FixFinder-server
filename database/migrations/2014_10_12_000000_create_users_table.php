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
  //location, profile picture, phone number, role (client or service provider), registration date, rating.
  public function up()
  {
    Schema::create('users', function (Blueprint $table) {
      $table->id();
      $table->string('email')->unique();
      $table->string('firstname');
      $table->string('lastname');
      $table->string('phone');
      $table->timestamp('email_verified_at')->nullable();
      $table->string('password');
      $table->foreignId('avatar_id')->nullable()->constrained('uploads')->cascadeOnDelete();
      $table->foreignId('location_id')->nullable()->constrained('locations')->cascadeOnDelete();
      $table->float('rating')->nullable();
      $table->rememberToken();
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
    Schema::dropIfExists('users');
  }
};
