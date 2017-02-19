<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Image
 * @package App
 * @property $orginalimage
 * @property $imagetype
 * @property $imagepath
 */
class Image extends Model
{

    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * @var string
     */
    protected $table = 'Images';

    /**
     * @var array
     */
    protected $fillable = ['orginalimage', 'imagetype', 'imagepath'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function imageable()
    {
        return $this->morphTo();
    }
}