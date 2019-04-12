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
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('type')->default('Member');
            $table->mediumText('bio')->nullable();
            $table->string('photo')->default('pen.png');
            $table->string('password');
            $table->string('team')->nullable();
            $table->string('skills')->nullable();
            $table->string('education')->nullable();
            $tabe->string('location')->nullable()->default('Windsor');
            
            
            
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
}
