<?php

namespace App\Repositories;

use App\Models\ApiUser;
use App\Models\Billing\BillingProfile;
use App\Models\Billing\BillingProfileOperator;
use App\Models\Billing\BillingProfileTiering;
use App\Models\Billing\BillingProfileOperatorPrice;
use App\Models\Billing\BillingProfileTieringOperator;
use App\Repositories\Repository as RepositoryContract;
use Illuminate\Database\Eloquent\Builder;

class BillingRepositories extends RepositoryContract
{

    /**
     * Create a new BillingRepository instance.
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
        return $this->model ?? new BillingProfile;
    }

    /**
     * Get Billing Builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function builder()
    {
        $builder = $this->model()->query();
        return $builder;
    }

    /**
     * Get Billing data
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function data()
    {
        return $this->builder()->get();
    }

    /**
     * Get Billing Name
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function billingName($BILLING_NAME)
    {
        return BillingProfile::where('NAME',$BILLING_NAME)->first();
    }

    /**
     * Get Billing Users
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function billingUsers($BILLING_PROFILE_ID)
    {
        return ApiUser::where('BILLING_PROFILE_ID',$BILLING_PROFILE_ID)->get();
    }

    /**
     * Get Operator Settings
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function operatorSettings($BILLING_PROFILE_ID)
    {
        return BillingProfileOperator::where('BILLING_PROFILE_ID',$BILLING_PROFILE_ID)->get();
    }

    /**
     * Get Tiering Settings
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function tieringSettings($BILLING_PROFILE_ID)
    {
        return BillingProfileTiering::where('BILLING_PROFILE_ID',$BILLING_PROFILE_ID)->get();
    }

    /**
     * Get Tiering Operator Settings
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function tieringOperatorSettings($BILLING_PROFILE_ID)
    {
        return BillingProfileTieringOperator::where('BILLING_PROFILE_ID',$BILLING_PROFILE_ID)
                            ->join(env('DB_DATABASE_BILL_PRICELIST').'.BILLING_PROFILE_OPERATOR_PRICE'
                                    , 'BILLING_PROFILE_TIERING_OPERATOR.BILLING_PROFILE_TIERING_OPERATOR_ID'
                                    , '='
                                    , env('DB_DATABASE_BILL_PRICELIST').'.BILLING_PROFILE_OPERATOR_PRICE.BILLING_PROFILE_TIERING_OPERATOR_ID')
                            ->get();
    }

    /**
     * Store new Billing to database
     *
     * @param  array $attributes
     * @param Billing $billing
     * @return bool
     */
    public function save(array $attributes = [], Billing $billing = null)
    {
        $billing = $billing ?? $this->model();

        $billing->fill($attributes);

        if (!empty($attributes['added_billing_profile_id'])) {
            $billing->BILLING_PROFILE_ID = $attributes['added_billing_profile_id'];
        }

        if (!empty($attributes['added_type'])) {
            $billing->BILLING_TYPE = $attributes['added_type'];
        }

        if (!empty($attributes['added_billing_name'])) {
            $billing->NAME = $attributes['added_billing_name'];
        }

        if (!empty($attributes['added_billing_description'])) {
            $billing->DESCRIPTION = $attributes['added_billing_description'];
        }

        if(empty($billing->CREATED_AT)){
            $billing->CREATED_AT = now();
        }

        $saved = $billing->save();

        /** Organize Billing Type */

        $type = $attributes['added_type'];
        if ($type == 'operator'){

            for ($i=0; $i<count($attributes['operatorPR']); $i++){
                if (empty($attributes['operatorOP'][$i])){
                    $attributes['operatorOP'][$i] = ['operator'=>'DEFAULT'];
                }
            }

            $this->processOperator($billing->BILLING_PROFILE_ID, $attributes['added_billing_users'], $attributes['operatorPR'], $attributes['operatorOP']);
        }else if ($type == 'tiering'){

            $this->processTiering($billing->BILLING_PROFILE_ID, $attributes['added_billing_users'], $attributes['tieringFR'], $attributes['tieringUP'], $attributes['tieringPR']);
        
        }else if ($type == 'tiering-operator'){

            for ($i=0; $i<count($attributes['tOperatorPR']); $i++){
                if (empty($attributes['tOperatorOP'][$i])){
                    $attributes['tOperatorOP'][$i] = ['operator'=>'DEFAULT'];
                }
            }

            $this->processTieringOperator(
                $billing->BILLING_PROFILE_ID, 
                $attributes['added_billing_users'], 
                $attributes['tOperatorFR'], 
                $attributes['tOperatorUP'],
                $attributes['tOperatorOP'],
                $attributes['tOperatorPR'], 
            );

        }

        return $saved;
    }

    /**
     * Process Billing Type Operator
     */
    
    public function processOperator($BILLING_PROFILE_ID = null, array $all_users = [], array $all_prices = [], array $all_operators = [])
    {
        /** 
         * Update multiple users
         */

        ApiUser::whereIn('USER_ID', $all_users)->update([
            'BILLING_PROFILE_ID' => $BILLING_PROFILE_ID
        ]);
        
        /** 
         * Insert multiple billing profile operator
         */
        
        $data = [];

        for ($i=0; $i<count($all_operators); $i++)
        {
            $data[] = ['BILLING_PROFILE_ID' => $BILLING_PROFILE_ID,'OP_ID' => $all_operators[$i]['operator'],'PER_SMS_PRICE' => $all_prices[$i]['price']
            ];
        }
        BillingProfileOperator::insert($data);
    }

    /**
     * Process Billing Type Tiering 
     * with new modified data file in tiering
     */
    
    public function processTiering($BILLING_PROFILE_ID = null, array $all_users = [], array $all_tiering_fr = [], array $all_tiering_up = [], array $all_tiering_pr = [])
    {

        /** 
         * Update multiple users
         */

        ApiUser::whereIn('USER_ID', $all_users)->update([
            'BILLING_PROFILE_ID' => $BILLING_PROFILE_ID
        ]);
        
        /** 
         * Insert multiple billing profile tiering
         */
        
        $data = [];

        for ($i=0; $i<count($all_tiering_fr); $i++)
        {
            if ($all_tiering_up[$i]['tr'] == 'MAX'){
                $all_tiering_up[$i]['tr'] = 9999999999;
            }
            
            $data[] = [
                'BILLING_PROFILE_ID' => $BILLING_PROFILE_ID,
                'SMS_COUNT_FROM' => $all_tiering_fr[$i]['tr'],'SMS_COUNT_UP_TO' => $all_tiering_up[$i]['tr'],'PER_SMS_PRICE' => $all_tiering_pr[$i]['price']
            ];
        }
        
        BillingProfileTiering::insert($data);
    }

    /**
     * Process Billing Type Tiering Operator
     */

    public function processTieringOperator($BILLING_PROFILE_ID = null, array $all_users = [], array $all_tiering_fr = [], array $all_tiering_up = [], array $all_tiering_op = [], array $all_tiering_pr = [])
    {
        /** 
         * Update multiple users
         */

        ApiUser::whereIn('USER_ID', $all_users)->update([
            'BILLING_PROFILE_ID' => $BILLING_PROFILE_ID
        ]);
        
        /** 
         * Insert multiple billing profile tiering operator
         */
        
        $billingProfileTieringOperatorID = 0;

        for ($i=0; $i<count($all_tiering_fr); $i++)
        {
            if (!is_null($all_tiering_fr[$i]['tr'])){

                if ($all_tiering_up[$i]['tr'] == 'MAX'){
                    $all_tiering_up[$i]['tr'] = 9999999999;
                }

                $bpto = new BillingProfileTieringOperator;
                $bpto->BILLING_PROFILE_ID = $BILLING_PROFILE_ID;
                $bpto->SMS_COUNT_FROM = $all_tiering_fr[$i]['tr'];
                $bpto->SMS_COUNT_UP_TO = $all_tiering_up[$i]['tr'];
                
                $bpto->save();
                
                $billingProfileTieringOperatorID = $bpto->BILLING_PROFILE_TIERING_OPERATOR_ID;
                
                $bpop = new BillingProfileOperatorPrice;
                $bpop->BILLING_PROFILE_TIERING_OPERATOR_ID = $billingProfileTieringOperatorID;
                $bpop->OP_ID = $all_tiering_op[$i]['operator'];
                $bpop->PER_SMS_PRICE = $all_tiering_pr[$i]['price'];
                
                $bpop->save();

            }else{
                
                $bpop = new BillingProfileOperatorPrice;
                $bpop->BILLING_PROFILE_TIERING_OPERATOR_ID = $billingProfileTieringOperatorID;
                $bpop->OP_ID = $all_tiering_op[$i]['operator'];
                $bpop->PER_SMS_PRICE = $all_tiering_pr[$i]['price'];
                
                $bpop->save();

            }
        }
    }


    /**
     * Update Billing to database
     *
     * @param  array $attributes
     * @param Billing $billing
     * @return bool
     */
    public function update(array $attributes = [])
    {

        $updated = 
        BillingProfile::where('BILLING_PROFILE_ID', $attributes["edited_billing_id"])
            ->update([
                'NAME' => $attributes["edited_name"],
                'DESCRIPTION' => $attributes["edited_description"],
                'UPDATED_AT' => now()
            ]);

        /** Organize Billing Type */

        $type = $attributes['edited_type'];
        if ($type == 'operator'){

            for ($i=0; $i<count($attributes['editOperatorPR']); $i++){
                if (empty($attributes['editOperatorOP'][$i])){
                    $attributes['editOperatorOP'][$i] = ['operator'=>'DEFAULT'];
                }
            }

            $this->processOperatorUpdate($attributes["edited_billing_id"], $attributes['edited_users'], $attributes['editOperatorPR'], $attributes['editOperatorOP']);
        }else if ($type == 'tiering'){

            $this->processTieringUpdate($attributes["edited_billing_id"], $attributes['edited_users'], $attributes['editTieringFR'], $attributes['editTieringUP'], $attributes['editTieringPR']);

        }else if ($type == 'tiering-operator'){
            
            for ($i=0; $i<count($attributes['edit_tOperatorPR']); $i++){
                if (empty($attributes['edit_tOperatorOP'][$i])){
                    $attributes['edit_tOperatorOP'][$i] = ['operator'=>'DEFAULT'];
                }
            }

            $this->processTieringOperatorUpdate(
                $attributes["edited_billing_id"], 
                $attributes['edited_users'], 
                $attributes['edit_tOperatorFR'], 
                $attributes['edit_tOperatorUP'],
                $attributes['edit_tOperatorOP'],
                $attributes['edit_tOperatorPR'], 
            );

        }

        return $updated;
    }

    /**
     * Process Billing Type Operator
     */
    
    public function processOperatorUpdate($BILLING_PROFILE_ID = null, array $all_users = [], array $all_prices = [], array $all_operators = [])
    {
        /**
         * Clean users
         */

        ApiUser::where('BILLING_PROFILE_ID', $BILLING_PROFILE_ID)->update([
            'BILLING_PROFILE_ID' => NULL
        ]);

        /** 
         * Update multiple users
         */

        ApiUser::whereIn('USER_ID', $all_users)->update([
            'BILLING_PROFILE_ID' => $BILLING_PROFILE_ID
        ]);
        
        /**
         * Delete older settings of billing operator
         */

        BillingProfileOperator::where('BILLING_PROFILE_ID',$BILLING_PROFILE_ID)->delete();

        /** 
         * Insert multiple billing profile operator
         */

        for ($i=0; $i<count($all_operators); $i++)
        {
            $data[] = [
                'BILLING_PROFILE_ID' => $BILLING_PROFILE_ID,
                'OP_ID' => $all_operators[$i]['operator'],
                'PER_SMS_PRICE' => $all_prices[$i]['price']
            ];
        }
        
        BillingProfileOperator::insert($data);
    }

    /**
     * Process Billing Type Tiering
     */
    
    public function processTieringUpdate($BILLING_PROFILE_ID = null, array $all_users = [], array $all_tiering_fr = [], array $all_tiering_up = [], array $all_tiering_pr = [])
    {

        /**
         * Clean users
         */
        
        ApiUser::where('BILLING_PROFILE_ID', $BILLING_PROFILE_ID)->update([
            'BILLING_PROFILE_ID' => NULL
        ]);

        /** 
         * Update multiple users
         */

        ApiUser::whereIn('USER_ID', $all_users)->update([
            'BILLING_PROFILE_ID' => $BILLING_PROFILE_ID
        ]);
        
        /**
         * Delete older settings of billing tiering
         */

        BillingProfileTiering::where('BILLING_PROFILE_ID',$BILLING_PROFILE_ID)->delete();

        /** 
         * Insert multiple billing profile tiering
         */
        
        $data = [];

        for ($i=0; $i<count($all_tiering_fr); $i++)
        {
            if ($all_tiering_up[$i]['tr'] == 'MAX'){
                $all_tiering_up[$i]['tr'] = 9999999999;
            }

            $data[] = [
                'BILLING_PROFILE_ID' => $BILLING_PROFILE_ID,
                'SMS_COUNT_FROM' => $all_tiering_fr[$i]['tr'],'SMS_COUNT_UP_TO' => $all_tiering_up[$i]['tr'],'PER_SMS_PRICE' => $all_tiering_pr[$i]['price']
            ];
        }
        
        BillingProfileTiering::insert($data);
    }

    /**
     * Process Billing Type Tiering Operator
     */

    public function processTieringOperatorUpdate($BILLING_PROFILE_ID = null, array $all_users = [], array $all_tiering_fr = [], array $all_tiering_up = [], array $all_tiering_op = [], array $all_tiering_pr = [])
    {

        /**
         * Clean users
         */
        
        ApiUser::where('BILLING_PROFILE_ID', $BILLING_PROFILE_ID)->update([
            'BILLING_PROFILE_ID' => NULL
        ]);
        
        /** 
         * Update multiple users
         */

        ApiUser::whereIn('USER_ID', $all_users)->update([
            'BILLING_PROFILE_ID' => $BILLING_PROFILE_ID
        ]);
        
        /**
         * Delete older settings of billing tiering-operator
         */

        $all_bpto = BillingProfileTieringOperator::where('BILLING_PROFILE_ID',$BILLING_PROFILE_ID)->get();

        foreach($all_bpto as $bpto){BillingProfileOperatorPrice::where('BILLING_PROFILE_TIERING_OPERATOR_ID',$bpto->BILLING_PROFILE_TIERING_OPERATOR_ID)->delete();
        }

        $billing = BillingProfileTieringOperator::where('BILLING_PROFILE_ID',$BILLING_PROFILE_ID)->delete();

        /** 
         * Insert multiple billing profile tiering-operator
         */

        $billingProfileTieringOperatorID = 0;

        for ($i=0; $i<count($all_tiering_fr); $i++)
        {
            if (!is_null($all_tiering_fr[$i]['tr'])){

                if ($all_tiering_up[$i]['tr'] == 'MAX'){
                    $all_tiering_up[$i]['tr'] = 9999999999;
                }

                $bpto = new BillingProfileTieringOperator;
                $bpto->BILLING_PROFILE_ID = $BILLING_PROFILE_ID;
                $bpto->SMS_COUNT_FROM = $all_tiering_fr[$i]['tr'];
                $bpto->SMS_COUNT_UP_TO = $all_tiering_up[$i]['tr'];
                
                $bpto->save();
                
                $billingProfileTieringOperatorID = $bpto->BILLING_PROFILE_TIERING_OPERATOR_ID;
                
                $bpop = new BillingProfileOperatorPrice;
                $bpop->BILLING_PROFILE_TIERING_OPERATOR_ID = $billingProfileTieringOperatorID;
                $bpop->OP_ID = $all_tiering_op[$i]['operator'];
                $bpop->PER_SMS_PRICE = $all_tiering_pr[$i]['price'];
                
                $bpop->save();

            }else{
                $bpop = new BillingProfileOperatorPrice;
                $bpop->BILLING_PROFILE_TIERING_OPERATOR_ID = $billingProfileTieringOperatorID;
                $bpop->OP_ID = $all_tiering_op[$i]['operator'];
                $bpop->PER_SMS_PRICE = $all_tiering_pr[$i]['price'];
                $bpop->save();
            }
        }
    }


    
    /**
     * Remove the Billing Profile from database.
     *
     * @param  int  $BILLING_ID
     * @return bool
     */
    public function delete($BILLING_ID,$BILLING_TYPE)
    {
        ApiUser::where('BILLING_PROFILE_ID', $BILLING_ID)->update([
            'BILLING_PROFILE_ID' => NULL
        ]);

        if ($BILLING_TYPE == 'operator'){
            $billing = BillingProfileOperator::where('BILLING_PROFILE_ID',$BILLING_ID)->delete();
        }else if ($BILLING_TYPE == 'tiering'){
            $billing = BillingProfileTiering::where('BILLING_PROFILE_ID',$BILLING_ID)->delete();
        }else if ($BILLING_TYPE == 'tiering-operator'){

            $all_bpto = BillingProfileTieringOperator::where('BILLING_PROFILE_ID',$BILLING_ID)->get();

            foreach($all_bpto as $bpto){BillingProfileOperatorPrice::where('BILLING_PROFILE_TIERING_OPERATOR_ID',$bpto->BILLING_PROFILE_TIERING_OPERATOR_ID)->delete();
            }

            $billing = BillingProfileTieringOperator::where('BILLING_PROFILE_ID',$BILLING_ID)->delete();
        }

        $billing = BillingProfile::where('BILLING_PROFILE_ID',$BILLING_ID)->delete();

        return (bool) $billing;
    }

}
