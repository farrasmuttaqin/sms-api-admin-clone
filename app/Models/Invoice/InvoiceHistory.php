<?php

namespace App\Models\Invoice;

use Illuminate\Database\Eloquent\Model;

class InvoiceHistory extends Model
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
    protected $table = 'INVOICE_HISTORY';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'INVOICE_HISTORY_ID';

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
        'INVOICE_HISTORY_ID',
        'INVOICE_PROFILE_ID',
        'INVOICE_NUMBER',
        'START_DATE',
        'DUE_DATE',
        'REF_NUMBER',
        'STATUS',
        'INVOICE_TYPE',
        'FILE_NAME',
        'LOCKED_AT',
        'CREATED_AT',
        'UPDATED_AT',
    ];
}
