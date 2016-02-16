<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Emailstat.
 *
 * @property int $id
 * @property string $event
 * @property string $domain
 * @property int $count
 * @property string $date
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Emailstat extends Model
{
    protected $dates = ['created_at', 'updated_at'];
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;
}
