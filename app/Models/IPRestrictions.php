<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IPRestrictions extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'sms_api_admin_mysql';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'USER_IP';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'USER_IP_ID';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = 
    [
        'USER_IP_ID',
        'USER_ID',
        'IP_ADDRESS',
    ];
}
