<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChecklistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checklists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('object_domain', 100);
            $table->string('object_id', 100);
            $table->mediumText('description');
            $table->boolean('is_completed')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->timestamp('due')->nullable();
            $table->smallInteger('urgency')->unsigned();
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
        Schema::dropIfExists('checklists');
    }
}
