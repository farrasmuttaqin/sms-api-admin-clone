<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sender extends Model
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
    protected $table = 'SENDER';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'SENDER_ID';

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
        'SENDER_ID',
        'SENDER_ENABLED',
        'USER_ID',
        'SENDER_NAME',
        'COBRANDER_ID',
    ];
}
