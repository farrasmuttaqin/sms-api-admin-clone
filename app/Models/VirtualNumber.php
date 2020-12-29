<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VirtualNumber extends Model
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
    protected $table = 'VIRTUAL_NUMBER';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'VIRTUAL_NUMBER_ID';

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
        'VIRTUAL_NUMBER_ID',
        'USER_ID',
        'DESTIONATION',
        'FORWARD_URL',
        'URL_INVALID_COUNT',
        'URL_ACTIVE',
        'URL_LAST_RETRY',
    ];
}
