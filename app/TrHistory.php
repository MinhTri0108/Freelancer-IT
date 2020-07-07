<?php
 namespace App;

use Illuminate\Database\Eloquent\Model;

class TrHistory extends Model{
    protected $table = 'txn_history';
    public $timestamps = false;
}