<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Emaildrop extends Model
{
    protected $dates = ['created_at', 'updated_at'];
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;
}
