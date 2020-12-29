<?php

namespace App\Models\Invoice;

use Illuminate\Database\Eloquent\Model;

class InvoiceSetting extends Model
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
    protected $table = 'INVOICE_SETTING';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'SETTING_ID';

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
        'SETTING_ID',
        'PAYMENT_PERIOD',
        'AUTHORIZED_NAME',
        'AUTHORIZED_POSITION',
        'NOTE_MESSAGE',
        'INVOICE_NUMBER_PREFIX',
        'LAST_INVOICE_NUMBER'
    ];
}
