<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkflowHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workflow_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('model_name')->index();
            $table->unsignedBigInteger('model_id')->index();
            $table->string('transition');
            $table->string('comment', 2000)->nullable();
            $table->string('from')->nullable();
            $table->string('from_text')->nullable();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('role_name')->nullable();
            $table->string('extra', 2000)->nullable();
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
        Schema::dropIfExists('workflow_histories');
    }
}
