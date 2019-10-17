<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->unsignedBigInteger('user_id')->index();
            
            $table->string('address')->nullable();
            $table->dateTime('birthday')->nullable();
            
            $table->enum('gender', ['male', 'female', 'transgender'])->nullable()->default(null);
            $table->enum('marital', ['alone', 'married', 'civil'])->nullable()->default(null);
            $table->longText('children')->nullable();
            
            $table->longText('education')->nullable();
            $table->longText('courses')->nullable();
            $table->longText('languages')->nullable();
                        
            $table->timestamps();
            
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('user_infos');
    }
}
