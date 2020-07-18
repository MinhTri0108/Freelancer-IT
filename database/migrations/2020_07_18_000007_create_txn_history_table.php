<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTxnHistoryTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'txn_history';

    /**
     * Run the migrations.
     * @table txn_history
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('of_user')->nullable()->default(null);
            $table->integer('relate_user')->nullable()->default(null);
            $table->string('type', 45)->nullable()->default(null);
            $table->decimal('amount', 13, 4)->nullable()->default(null);
            $table->tinyInteger('is_in')->nullable()->default(null);
            $table->text('description')->nullable()->default(null);
            $table->integer('created_at')->nullable()->default(null);
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
