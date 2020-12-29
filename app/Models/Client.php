<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
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
    protected $table = 'CLIENT';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'CLIENT_ID';

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
        'CLIENT_ID',
        'ARCHIVED_DATE',
        'COMPANY_NAME',
        'COMPANY_URL',
        'COUNTRY_CODE',
        'CONTACT_NAME',
        'CONTACT_EMAIL',
        'CONTACT_PHONE',
        'CONTACT_ADDRESS',
        'CREATED_AT',
        'UPDATED_AT',
        'CREATED_BY',
        'UPDATED_BY',
        'CUSTOMER_ID'
    ];
}
