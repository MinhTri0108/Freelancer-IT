<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'projects';

    /**
     * Run the migrations.
     * @table projects
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id');
            $table->string('name', 100);
            $table->text('description');
            $table->text('skills_required');
            $table->string('state', 45)->nullable()->default('recruiting');
            $table->text('file_uploaded')->nullable()->default(null);
            $table->string('pay_range', 45)->nullable()->default(null);
            $table->integer('freelancer_id')->nullable()->default(null);
            $table->integer('bid_end_at')->nullable()->default(null);
            $table->integer('started_at')->nullable()->default(null);
            $table->integer('ended_at')->nullable()->default(null);
            $table->integer('created_at')->nullable()->default(null);
            $table->integer('updated_at')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
}
