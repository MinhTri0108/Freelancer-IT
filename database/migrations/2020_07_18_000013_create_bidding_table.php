<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBiddingTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'bidding';

    /**
     * Run the migrations.
     * @table bidding
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('project_id');
            $table->integer('user_id');
            $table->tinyInteger('passed')->nullable()->default('0');
            $table->integer('bid_amount')->nullable()->default('0');
            $table->integer('period')->nullable()->default('7');
            $table->string('proposal', 150);
            $table->text('milestones')->nullable()->default(null);

            $table->unique(["project_id", "user_id"], 'unique_bid');
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
