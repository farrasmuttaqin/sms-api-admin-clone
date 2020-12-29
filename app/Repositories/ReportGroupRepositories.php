<?php

namespace App\Repositories;

use App\Models\ApiUser;
use App\Models\Billing\BillingProfile;
use App\Models\Billing\ReportGroup;
use App\Repositories\Repository as RepositoryContract;
use Illuminate\Database\Eloquent\Builder;

class ReportGroupRepositories extends RepositoryContract
{

    /**
     * Create a new ReportGroupRepositories instance.
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
        return $this->model ?? new ReportGroup;
    }

    /**
     * Get BILLING_REPORT_GROUP Builder
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
     * Get Billing Report Group Users
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function billingUsers($BILLING_REPORT_GROUP_ID)
    {
        return ApiUser::where('BILLING_REPORT_GROUP_ID',$BILLING_REPORT_GROUP_ID)->get();
    }

    /**
     * Store new BILLING_REPORT_GROUP_ID to database
     *
     * @param  array $attributes
     * @param ReportGroup $reportGroup
     * @return bool
     */
    public function save(array $attributes = [], ReportGroup $reportGroup = null)
    {
        $reportGroup = $reportGroup ?? $this->model();

        $reportGroup->fill($attributes);

        if (!empty($attributes['added_billing_report_group_id'])) {
            $reportGroup->BILLING_REPORT_GROUP_ID = $attributes['added_billing_report_group_id'];
        }

        if (!empty($attributes['added_report_group_name'])) {
            $reportGroup->NAME = $attributes['added_report_group_name'];
        }

        if (!empty($attributes['added_report_group_description'])) {
            $reportGroup->DESCRIPTION = $attributes['added_report_group_description'];
        }

        if(empty($reportGroup->CREATED_AT)){
            $reportGroup->CREATED_AT = now();
        }

        $saved = $reportGroup->save();

        /** 
         * Update multiple users
         */
        
        ApiUser::whereIn('USER_ID', $attributes['added_report_group_users'])->update([
            'BILLING_REPORT_GROUP_ID' => $reportGroup->BILLING_REPORT_GROUP_ID
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

        ApiUser::where('BILLING_REPORT_GROUP_ID', $attributes["edited_rg_id"])->update([
            'BILLING_REPORT_GROUP_ID' => NULL
        ]);
        
        $updated = 
        ReportGroup::where('BILLING_REPORT_GROUP_ID', $attributes["edited_rg_id"])
            ->update([
                'NAME' => $attributes["edited_report_group_name"],
                'DESCRIPTION' => $attributes["edited_description"],
                'UPDATED_AT' => now()
            ]);

        /** 
         * Update multiple users
         */

        ApiUser::whereIn('USER_ID', $attributes['edited_users'])->update([
            'BILLING_REPORT_GROUP_ID' => $attributes["edited_rg_id"]
        ]);

        return $updated;
    }

    /**
     * Remove the BILLING_TIERING_GROUP from database.
     *
     * @param  int  $BILLING_TIERING_GROUP_ID
     * @return bool
     */
    public function delete($BILLING_REPORT_GROUP_ID)
    {
        ApiUser::where('BILLING_REPORT_GROUP_ID', $BILLING_REPORT_GROUP_ID)->update([
            'BILLING_REPORT_GROUP_ID' => NULL
        ]);

        $billing=ReportGroup::where('BILLING_REPORT_GROUP_ID',$BILLING_REPORT_GROUP_ID)->delete();

        return (bool) $billing;
    }

}
