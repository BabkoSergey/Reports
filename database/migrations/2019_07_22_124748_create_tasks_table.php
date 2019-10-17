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
            $table->bigIncrements('id');
            
            $table->enum('type', ['project', 'estimate', 'mentoring', 'research', 'education', 'teamwork'])->default('project');
            $table->enum('add_type', ['plan', 'report'])->default('report');
            $table->unsignedBigInteger('resourse')->index()->nullable();
            $table->unsignedBigInteger('user_id')->index()->nullable();
            
            $table->string('name');
            $table->text('todo')->nullable();
            $table->text('note')->nullable();
            
                        
            $table->timestamps();
            
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
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
