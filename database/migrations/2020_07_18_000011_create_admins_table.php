<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'admins';

    /**
     * Run the migrations.
     * @table admins
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('username');
            $table->string('password');
            $table->rememberToken();
            $table->integer('level')->nullable()->default(null);
            $table->string('full_name', 45);
            $table->string('email');
            $table->string('phone', 10)->nullable()->default(null);
            $table->tinyInteger('is_changedpw')->nullable()->default('0');

            $table->unique(["username"], 'username_UNIQUE');

            $table->unique(["email"], 'email_UNIQUE');
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
