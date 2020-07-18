<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWithdrawReqTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'withdraw_req';

    /**
     * Run the migrations.
     * @table withdraw_req
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->nullable()->default(null);
            $table->string('paypal_email')->nullable()->default(null);
            $table->decimal('amount', 13, 4)->nullable()->default(null);
            $table->integer('approved_at')->nullable()->default('0');
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
