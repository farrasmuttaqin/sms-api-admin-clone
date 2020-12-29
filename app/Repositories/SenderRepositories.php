<?php

namespace App\Repositories;

use App\Models\Sender;
use App\Repositories\Repository as RepositoryContract;
use Illuminate\Database\Eloquent\Builder;

class SenderRepositories extends RepositoryContract
{

    /**
     * Create a new sender instance.
     *
     * @return void
     */
    function __construct()
    {
        $this->model = $this->model();
    }

    /**
     * Get Model instance
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function model()
    {
        return $this->model ?? new Sender;
    }

    /**
     * Get sender Builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function builder()
    {
        $builder = $this->model()->query();
        return $builder;
    }

    /**
     * Get model data
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function data($USER_ID)
    {
        return Sender::where('USER_ID',$USER_ID)
                        ->get();
    }

    /**
     * Store new sender to database
     *
     * @param  array $attributes
     * @param Sender $sender
     * @return bool
     */
    public function save(array $attributes = [], Sender $sender = null)
    {
        $sender = $sender ?? $this->model();

        $sender->fill($attributes);

        if (!empty($attributes['added_sender_id'])) {
            $sender->SENDER_ID = $attributes['added_sender_id'];
        }

        if ($attributes['added_sender_enabled'] == 0 || !empty($attributes['added_sender_enabled'])){
            $sender->SENDER_ENABLED = (int)$attributes['added_sender_enabled'];
        }

        if (!empty($attributes['added_user_id'])) {
            $sender->USER_ID = $attributes['added_user_id'];
        }

        if (!empty($attributes['added_sender_name'])) {
            $sender->SENDER_NAME = $attributes['added_sender_name'];
        }

        if (!empty($attributes['added_cobrander_id'])) {
            $sender->COBRANDER_ID = $attributes['added_cobrander_id'];
        }

        $saved = $sender->save();

        return $saved;
    }

    /**
     * Update sender
     *
     * @param  array $attributes
     * @return bool
     */
    public function update(array $attributes = [])
    {   
        $updated = 
            Sender::where('SENDER_ID', (int)$attributes["edited_sender_id"])
                ->update([
                    'SENDER_ENABLED' => (int)$attributes["edited_sender_enabled"], 
                    'USER_ID' => (int)$attributes["edited_user_id"],
                    'SENDER_NAME' => $attributes["edited_sender_name"],
                    'COBRANDER_ID' => $attributes["edited_cobrander_id"]
                ]);
                
        return $updated;
    }

}
