<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimingToEstimatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estimates', function (Blueprint $table) {
            
            $table->longText('timing')->nullable();
            $table->enum('view', ['estimate-dev', 'estimate-manager', 'estimate-customer'])->default('estimate-dev');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estimates', function (Blueprint $table) {
            
            $table->dropColumn('timing');
            $table->dropColumn('view');
            
        });
    }
}
