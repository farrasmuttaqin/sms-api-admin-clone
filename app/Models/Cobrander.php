<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cobrander extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'bill_u_message_mysql';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'COBRANDER';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'COBRANDER_ID';

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
        'COBRANDER_ID',
        'AGENT_ID',
        'COBRANDER_NAME',
        'OPERATOR_NAME',
        'CREATED_AT',
        'UPDATED_AT_COBRANDER',
        'CREATED_BY',
        'UPDATED_BY_COBRANDER'
    ];
}
