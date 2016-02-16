<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Emaildrop.
 *
 * @property int $id
 * @property string $sender
 * @property string $subject
 * @property string $Spf
 * @property float $Spamscore
 * @property string $Spamflag
 * @property string $DkimCheck
 * @property bool $public
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $recipient
 * @property string $bodyplain
 * @property string $messageheaders
 */
class Emaildrop extends Model
{
    protected $dates = ['created_at', 'updated_at'];
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;
}
