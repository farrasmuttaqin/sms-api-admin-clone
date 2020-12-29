<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
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
    protected $table = 'AGENT';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'AGENT_ID';

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
        'AGENT_ID',
        'AGENT_NAME',
        'AGENT_NAME_QUEUE',
        'AGEN_DESCRIPTION',
        'CREATED_AT',
        'UPDATED_AT',
        'CREATED_BY',
        'UPDATED_BY'
    ];
}
