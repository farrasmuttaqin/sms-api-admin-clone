<?php

namespace App\Repositories;

use App\Models\ApiUser;
use App\Models\Billing\BillingProfile;
use App\Models\Billing\TieringGroup;
use App\Repositories\Repository as RepositoryContract;
use Illuminate\Database\Eloquent\Builder;

class TieringGroupRepositories extends RepositoryContract
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
        return $this->model ?? new TieringGroup;
    }

    /**
     * Get BILLING_TIERING_GROUP Builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function builder()
    {
        $builder = $this->model()->query();
        return $builder;
    }

    /**
     * Get BILLING_TIERING_GROUP data
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function data()
    {
        return $this->builder()->get();
    }

    /**
     * Get BILLING_TIERING_GROUP joined data
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function allUsers()
    {
    	/**
    	 * Removing cobrander join to get all tiering group users.
    	 */
    	
        return ApiUser::where(env('DB_DATABASE_BILL_PRICELIST').'.BILLING_PROFILE.BILLING_TYPE','tiering')
            ->join(env('DB_DATABASE_BILL_PRICELIST').'.BILLING_PROFILE', 'USER.BILLING_PROFILE_ID', '=', env('DB_DATABASE_BILL_PRICELIST').'.BILLING_PROFILE.BILLING_PROFILE_ID')
            ->join(env('DB_DATABASE_SMS_API_ADMIN').'.ADMIN', 'USER.CREATED_BY', '=', env('DB_DATABASE_SMS_API_ADMIN').'.ADMIN.ADMIN_ID')
            ->join('CLIENT', 'CLIENT.CLIENT_ID', '=', 'USER.CLIENT_ID')
            ->get();
    }

    /**
     * Get Billing Tiering Group Users
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function billingUsers($BILLING_TIERING_GROUP_ID)
    {
        return ApiUser::where('BILLING_TIERING_GROUP_ID',$BILLING_TIERING_GROUP_ID)->get();
    }

    /**
     * Store new BILLING_TIERING_GROUP to database
     *
     * @param  array $attributes
     * @param TieringGroup $tieringGroup
     * @return bool
     */
    public function save(array $attributes = [], TieringGroup $tieringGroup = null)
    {
        $tieringGroup = $tieringGroup ?? $this->model();

        $tieringGroup->fill($attributes);

        if (!empty($attributes['added_billing_tiering_group_id'])) {
            $tieringGroup->BILLING_TIERING_GROUP_ID = $attributes['added_billing_tiering_group_id'];
        }

        if (!empty($attributes['added_tiering_group_name'])) {
            $tieringGroup->NAME = $attributes['added_tiering_group_name'];
        }

        if (!empty($attributes['added_tiering_group_description'])) {
            $tieringGroup->DESCRIPTION = $attributes['added_tiering_group_description'];
        }

        if(empty($tieringGroup->CREATED_AT)){
            $tieringGroup->CREATED_AT = now();
        }

        $saved = $tieringGroup->save();

        /** 
         * Update multiple users
         */
        
        ApiUser::whereIn('USER_ID', $attributes['added_tiering_group_users'])->update([
            'BILLING_TIERING_GROUP_ID' => $tieringGroup->BILLING_TIERING_GROUP_ID
        ]);

        return $saved;
    }
    
    /**
     * Update Billing tiering group to database
     *
     * @param  array $attributes
     * @return bool
     */
    public function update(array $attributes = [])
    {
        /**
         * Clean users
         */

        ApiUser::where('BILLING_TIERING_GROUP_ID', $attributes["edited_tg_id"])->update([
            'BILLING_TIERING_GROUP_ID' => NULL
        ]);


        $updated = 
        TieringGroup::where('BILLING_TIERING_GROUP_ID', $attributes["edited_tg_id"])
            ->update([
                'NAME' => $attributes["edited_tiering_group_name"],
                'DESCRIPTION' => $attributes["edited_description"],
                'UPDATED_AT' => now()
            ]);

        /** 
         * Update multiple users
         */

        ApiUser::whereIn('USER_ID', $attributes['edited_users'])->update([
            'BILLING_TIERING_GROUP_ID' => $attributes["edited_tg_id"]
        ]);

        return $updated;
    }

    /**
     * Remove the BILLING_TIERING_GROUP from database.
     *
     * @param  int  $BILLING_TIERING_GROUP_ID
     * @return bool
     */
    public function delete($BILLING_TIERING_GROUP_ID)
    {
        ApiUser::where('BILLING_TIERING_GROUP_ID', $BILLING_TIERING_GROUP_ID)->update([
            'BILLING_TIERING_GROUP_ID' => NULL
        ]);

        $billing=TieringGroup::where('BILLING_TIERING_GROUP_ID',$BILLING_TIERING_GROUP_ID)->delete();

        return (bool) $billing;
    }

}
