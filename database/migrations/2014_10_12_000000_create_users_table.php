<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('photo')->nullable();
            $table->string('username')->index()->unique();
            $table->string('email')->index()->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        \DB::update('ALTER TABLE users AUTO_INCREMENT = 1001');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}
