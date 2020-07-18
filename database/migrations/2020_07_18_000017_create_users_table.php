<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'users';

    /**
     * Run the migrations.
     * @table users
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('username');
            $table->string('email');
            $table->integer('email_verified_at')->nullable()->default(null);
            $table->string('password');
            $table->rememberToken();
            $table->tinyInteger('is_freelancer')->nullable()->default('1');
            $table->string('phone', 10)->nullable()->default(null);
            $table->text('skills')->nullable()->default(null);
            $table->string('current_job', 45)->nullable()->default(null);
            $table->tinyInteger('payment')->nullable()->default('0');
            $table->decimal('balances', 13, 4)->nullable()->default('0.0000');
            $table->string('first_name', 45)->nullable()->default(null);
            $table->string('last_name', 45)->nullable()->default(null);
            $table->text('introduce')->nullable()->default(null);
            $table->string('address', 100)->nullable()->default(null);
            $table->string('city_province', 50)->nullable()->default(null);
            $table->string('avatar')->nullable()->default('anonymous.jpg');

            $table->unique(["email"], 'users_email_unique');
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
