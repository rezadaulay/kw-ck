<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('checklist_id');
            $table->foreign('checklist_id')->references('id')->on('checklists')->onDelete('cascade');
            $table->mediumText('description');
            $table->boolean('is_completed')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('due')->nullable();
            $table->smallInteger('urgency')->unsigned();
            $table->string('updated_by', 100)->nullable();
            $table->string('assignee_id', 100)->nullable();
            $table->string('task_id', 100)->nullable();
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
        Schema::dropIfExists('items');
    }
}
