<?php

namespace App\Models\Invoice;

use Illuminate\Database\Eloquent\Model;

class InvoiceProfile extends Model
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
    protected $table = 'INVOICE_PROFILE';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'INVOICE_PROFILE_ID';

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
        'INVOICE_PROFILE_ID',
        'CLIENT_ID',
        'INVOICE_BANK_ID',
        'AUTO_GENERATE',
        'CREATED_AT',
        'APPROVED_NAME',
        'APPROVED_POSITION',
        'UPDATED_AT',
    ];
}
