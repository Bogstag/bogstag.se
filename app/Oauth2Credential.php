<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Oauth2Credential
 * @package App
 */
class Oauth2Credential extends Model
{
    /**
     * @var array
     */
    protected $dates = ['expires', 'created_at', 'updated_at'];

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var bool
     */
    public $incrementing = true;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * This is done because my prod server returns this as string not int.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer'];

    /**
     * Indicates what can be submitted to update.
     *
     * @var array
     */
    protected $fillable = ['provider'];
}
