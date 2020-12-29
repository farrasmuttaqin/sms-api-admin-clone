<?php

namespace App\Repositories;

use App\Models\Agent;
use App\Repositories\Repository as RepositoryContract;
use Illuminate\Database\Eloquent\Builder;

class AgentRepositories extends RepositoryContract
{

    /**
     * Create a new AgentRepository instance.
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
        return $this->model ?? new Agent;
    }

    /**
     * Get Agent Builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function builder()
    {
        $builder = $this->model()->query();
        return $builder;
    }

    /**
     * Get Agent data
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function data()
    {
        return $this->builder()->get();
    }

    /**
     * Get Agent joined data
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function allJoinedData()
    {
        return Agent::join(env('DB_DATABASE_SMS_API_ADMIN').'.ADMIN', 'AGENT.CREATED_BY', '=', env('DB_DATABASE_SMS_API_ADMIN').'.ADMIN.ADMIN_ID')
                        ->get();
    }

    /**
     * Store new agent to database
     *
     * @param  array $attributes
     * @param Agent $agent
     * @return bool
     */
    public function save(array $attributes = [], Agent $agent = null)
    {
        $agent = $agent ?? $this->model();

        $agent->fill($attributes);

        if (!empty($attributes['added_agent_id'])) {
            $agent->AGENT_ID = $attributes['added_agent_id'];
        }

        if (!empty($attributes['added_agent_name'])) {
            $agent->AGENT_NAME = $attributes['added_agent_name'];
        }

        if (!empty($attributes['added_agent_queue_name'])) {
            $agent->AGENT_NAME_QUEUE = $attributes['added_agent_queue_name'];
        }

        if (!empty($attributes['added_agent_description'])) {
            $agent->AGENT_DESCRIPTION = $attributes['added_agent_description'];
        }

        if(empty($agent->CREATED_BY)){
            $agent->CREATED_BY = $this->user()->getKey();
        }

        if(empty($agent->CREATED_AT)){
            $agent->CREATED_AT = now();
        }

        $saved = $agent->save();

        return $saved;
    }

    /**
     * Update agent
     *
     * @param  array $attributes
     * @param Agent $agent
     * @return bool
     */
    public function update(array $attributes = [])
    {
        $updated = 
            Agent::where('AGENT_ID', $attributes["edited_agent_id"])
                ->update([
                    'AGENT_NAME' => $attributes["edited_agent_name"], 
                    'AGENT_NAME_QUEUE' => $attributes["edited_agent_queue_name"],
                    'AGENT_DESCRIPTION' => $attributes["edited_agent_description"],
                    'UPDATED_AT' => now(),
                    'UPDATED_BY' => $this->user()->getKey()
                ]);

        return $updated;
    }
    
    /**
     * Remove the Agent from database.
     *
     * @param  int  $AGENT_ID
     * @return bool
     */
    public function delete($AGENT_ID)
    {
        $agent=Agent::where('AGENT_ID',$AGENT_ID)->delete();

        return (bool) $agent;
    }

}
