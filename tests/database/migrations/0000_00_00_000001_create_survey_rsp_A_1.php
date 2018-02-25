<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveyRspA1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rsp_a_1', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->morphs('respondent');
            $table->integer('survey_id')->unsigned();
            $table->integer('p1q1')->nullable();
            $table->string('p1q2')->nullable();
            $table->integer('p2q1')->nullable();
            $table->integer('p2q2')->nullable();
            $table->string('last_page');
            $table->integer('duration')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finalized_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('survey_id')->references('id')->on('surveys')->onDelete('restrict');
            $table->index(['respondent_type']);
            $table->index(['survey_id']);
            $table->index(['started_at', 'finalized_at', 'survey_id']);
        });

        Illuminate\Database\Eloquent\Model::unguard();
        \Sirs\Surveys\Models\Survey::firstOrCreate([
            "name"=>"A1",
            "version"=>"1",
            "file_name"=>"a.xml",
            "response_table"=>"rsp_a_1"
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('rsp_a_1');
        $s = \Sirs\Surveys\Models\Survey::where('name', 'A1')->where('version', '1');
        $s->delete();
    }
}
