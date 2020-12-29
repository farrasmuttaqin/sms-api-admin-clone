<?php

namespace App\Models\Billing;

use Illuminate\Database\Eloquent\Model;

class BillingProfileTieringOperator extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'bill_pricelist_mysql';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'BILLING_PROFILE_TIERING_OPERATOR';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'BILLING_PROFILE_TIERING_OPERATOR_ID';

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
        'BILLING_PROFILE_TIERING_OPERATOR_ID',
        'BILLING_PROFILE_ID',
        'SMS_COUNT_FROM',
        'SMS_COUNT_UP_TO'
    ];
}
