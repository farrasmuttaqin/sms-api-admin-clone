<?php

namespace App\Repositories;

use App\Models\Cobrander;
use App\Repositories\Repository as RepositoryContract;
use Illuminate\Database\Eloquent\Builder;

class CobranderRepositories extends RepositoryContract
{

    /**
     * Create a new CobranderRepository instance.
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
        return $this->model ?? new Cobrander;
    }

    /**
     * Get Cobrander Builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function builder()
    {
        $builder = $this->model()->query();
        return $builder;
    }

    /**
     * Get Cobrander data
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function data()
    {
        return $this->builder()->groupBy('COBRANDER_NAME')->get();
    }

    /**
     * Get Cobrander joined data
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function allJoinedData()
    {
        return Cobrander::join(env('DB_DATABASE_SMS_API_ADMIN').'.ADMIN', 'COBRANDER.CREATED_BY', '=', env('DB_DATABASE_SMS_API_ADMIN').'.ADMIN.ADMIN_ID')
                        ->join('AGENT', 'COBRANDER.AGENT_ID', '=', 'AGENT.AGENT_ID')
                        ->orderBy('COBRANDER.COBRANDER_NAME','ASC')
                        ->get();
    }

    /**
     * Store new Cobrander to database
     *
     * @param  array $attributes
     * @param Cobrander $Cobrander
     * @return bool
     */
    public function save(array $attributes = [], Cobrander $cobrander = null)
    {
        for ($i=0; $i<count($attributes['added_operator_name']); $i++){
            $cobrander = new Cobrander;

            if (!empty($attributes['added_cobrander_id'])) {
                $cobrander->COBRANDER_ID = $attributes['added_cobrander_id'];
            }

            if (!empty($attributes['added_cobrander_name'])) {
                $cobrander->COBRANDER_NAME = $attributes['added_cobrander_name'];
            }

            if (!empty($attributes['added_agent_id'])) {
                $cobrander->AGENT_ID = $attributes['added_agent_id'];
            }

            if (!empty($attributes['added_operator_name'][$i])) {
                $cobrander->OPERATOR_NAME = $attributes['added_operator_name'][$i];
            }

            if(empty($cobrander->CREATED_BY)){
                $cobrander->CREATED_BY = $this->user()->getKey();
            }

            if(empty($cobrander->CREATED_AT)){
                $cobrander->CREATED_AT = now();
            }

            $saved = $cobrander->save();
        }
        

        return $saved;
    }

    /**
     * Update cobrander
     *
     * @param  array $attributes
     * @param Cobrander $cobrander
     * @return bool
     */
    public function update(array $attributes = [])
    {
        /**
         * Delete current cobrander
         */
        $array = explode(',',$attributes['edited_cobrander_id']);
        $delete = Cobrander::whereIn('COBRANDER_ID',$array)->delete();

        /**
         * Insert new cobrander
         */
        for ($i=0; $i<count($attributes["edited_operator_name"]); $i++){
            $cobrander = new Cobrander;

            $cobrander->COBRANDER_NAME  = $attributes["edited_cobrander_name"];
            $cobrander->AGENT_ID        = $attributes["edited_agent_id"];
            $cobrander->OPERATOR_NAME   = $attributes["edited_operator_name"][$i];
            $cobrander->CREATED_BY      = $this->user()->getKey();
            $cobrander->CREATED_AT      = now();
            $cobrander->UPDATED_BY      = $this->user()->getKey();
            $cobrander->UPDATED_AT      = now();

            $saved                      = $cobrander->save();
        }

        return $saved;
    }
    
    /**
     * Remove the Cobrander from database.
     *
     * @param  int  $COBRANDER_ID
     * @return bool
     */
    public function delete($COBRANDER_ID)
    {
        $cobrander=Cobrander::whereIn('COBRANDER_ID',$COBRANDER_ID)->delete();

        return (bool) $cobrander;
    }

}
