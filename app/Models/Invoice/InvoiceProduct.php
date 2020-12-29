<?php

namespace App\Models\Invoice;

use Illuminate\Database\Eloquent\Model;

class InvoiceProduct extends Model
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
    protected $table = 'INVOICE_PRODUCT';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'PRODUCT_ID';

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
        'PRODUCT_ID',
        'PRODUCT_NAME',
        'PERIOD',
        'IS_PERIOD',
        'UNIT_PRICE',
        'QTY',
        'USE_REPORT',
        'REPORT_NAME',
        'OPERATOR',
        'OWNER_TYPE',
        'OWNER_ID'
    ];
}
