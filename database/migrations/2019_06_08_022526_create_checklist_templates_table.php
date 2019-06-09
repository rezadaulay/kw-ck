<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChecklistTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checklist_templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100);
            $table->text('checklist');
            $table->text('items');
            $table->text('domains')->nullable();
        });
        // Schema::create('checklist_template_domains', function (Blueprint $table) {
        //     // $table->bigIncrements('id');
        //     $table->unsignedBigInteger('checklist_template_id');
        //     $table->foreign('checklist_template_id')->references('id')->on('checklist_templates')->onDelete('cascade');
        //     $table->mediumInteger('object_id')->index();
        //     $table->string('object_domain', 100);
        // });
        // Schema::create('checklist_templates', function (Blueprint $table) {
        //     $table->string('description', 200);
        //     $table->smallInteger('due_interval')->unsigned();
        //     // $table->timestamps();
        // });
        // Schema::create('checklist_template_items', function (Blueprint $table) {
        //     $table->string('description', 200);
        //     $table->smallInteger('urgency')->unsigned();
        //     $table->smallInteger('due_interval')->unsigned();
        //     $table->string('due_unit', 6);
        //     // $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('checklist_template_domains');
        Schema::dropIfExists('checklist_templates');
    }
}
