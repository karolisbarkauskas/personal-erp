<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYoutrackRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('youtrack_records', function (Blueprint $table) {
            $table->id();
            $table->string('group_name');
            $table->string('item');
            $table->text('item_summary');
            $table->text('estimation');
            $table->text('spent_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('youtrack_records');
    }
}
