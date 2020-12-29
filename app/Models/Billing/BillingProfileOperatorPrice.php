<?php

namespace App\Models\Billing;

use Illuminate\Database\Eloquent\Model;

class BillingProfileOperatorPrice extends Model
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
    protected $table = 'BILLING_PROFILE_OPERATOR_PRICE';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'BILLING_PROFILE_OPERATOR_PRICE_ID';

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
        'BILLING_PROFILE_OPERATOR_PRICE_ID',
        'BILLING_PROFILE_TIERING_OPERATOR_ID',
        'OP_ID',
        'PER_SMS_PRICE'
    ];
}
