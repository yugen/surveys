<?php

use Illuminate\Database\Migrations\Migration;

class CreateResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('responses', function ($table) {
            $table->increments('id');
            $table->integer('survey_id')->unsigned();
            $table->morphs('respondent');
            $table->json('response_data')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->string('last_page')->nullable();
            $table->timestamp('finalized_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(array('survey_id', 'surveys'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('responses');
    }
}
