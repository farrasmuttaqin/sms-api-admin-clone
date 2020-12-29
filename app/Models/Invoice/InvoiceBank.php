<?php

namespace App\Models\Invoice;

use Illuminate\Database\Eloquent\Model;

class InvoiceBank extends Model
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
    protected $table = 'INVOICE_BANK';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'INVOICE_BANK_ID';

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
        'INVOICE_BANK_ID',
        'BANK_NAME',
        'ADDRESS',
        'ACCOUNT_NAME',
        'ACCOUNT_NUMBER'
    ];
}
