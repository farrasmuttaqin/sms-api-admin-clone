<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CREDIT extends Model
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
    protected $table = 'CREDIT_TRANSACTION';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'CREDIT_TRANSACTION_ID';

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
        'CREDIT_TRANSACTION_ID',
        'TRANSACTION_REF',
        'USER_ID',
        'CREDIT_REQUESTER',
        'CREDIT_AMOUNT',
        'CREDIT_PRICE',
        'CURRENCY_CODE',
        'CURRENT_BALANCE',
        'PREVIOUS_BALANCE',
        'PAYMENT_METHOD',
        'PAYMENT_DATE',
        'PAYMENT_ACK',
        'CREATED_BY',
        'CREATED_DATE',
        'UPDATED_BY',
        'UPDATED_DATE',
        'TRANSACTION_REMARK'
    ];
}
