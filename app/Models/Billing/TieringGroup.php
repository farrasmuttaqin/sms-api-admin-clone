<?php

namespace App\Models\Billing;

use Illuminate\Database\Eloquent\Model;

class TieringGroup extends Model
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
    protected $table = 'BILLING_TIERING_GROUP';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'BILLING_TIERING_GROUP_ID';

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
        'BILLING_TIERING_GROUP_ID',
        'NAME',
        'DESCRIPTION',
        'CREATED_AT',
        'UPDATED_AT'
    ];
}
