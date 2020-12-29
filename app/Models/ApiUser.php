<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiUser extends Model
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
    protected $table = 'USER';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'USER_ID';

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
        'USER_ID',
        'version',
        'USER_NAME',
        'PASSWORD',
        'ACTIVE',
        'COUNTER',
        'CREDIT',
        'LAST_ACCESS',
        'CREATED_DATE',
        'UPDATED_DATE',
        'CREATED_BY',
        'UPDATED_BY',
        'COBRANDER_ID',
        'CLIENT_ID',
        'DELIVERY_STATUS_URL',
        'URL_INVALID_COUNT',
        'URL_ACTIVE',
        'URL_LAST_RETRY',
        'USE_BLACKLIST',
        'IS_POSTPAID',
        'TRY_COUNT',
        'INACTIVE_REASON',
        'DATETIME_TRY',
        'BILLING_PROFILE_ID',
        'BILLING_REPORT_GROUP_ID',
        'BILLING_TIERING_GROUP_ID',
        'IS_OJK'
    ];
}
