<?php

namespace App\Repositories;

use App\Models\Credit;
use App\Models\ApiUser;
use App\Repositories\Repository as RepositoryContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class CreditRepositories extends RepositoryContract
{

    /**
     * Create a new CreditRepositories instance.
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
        return $this->model ?? new Credit;
    }

    /**
     * Get Credit Builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function builder()
    {
        $builder = $this->model()->query();
        return $builder;
    }

    /**
     * Get Credit data
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function data()
    {
        return $this->builder()->get();
    }

    /**
     * Get join data with admin table
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function allJoinedData($USER_ID)
    {
        return Credit::where('USER_ID',$USER_ID)
                        ->join('ADMIN', 'CREDIT_TRANSACTION.CREATED_BY', '=', 'ADMIN.ADMIN_ID')
                        ->get();
    }

    /**
     * Top Up new credit to database
     *
     * @param  array $attributes
     * @param Credit $credit
     * @return bool
     */
    public function top_up(array $attributes = [], Credit $credit = null)
    {
        $credit = $credit ?? $this->model();

        $credit->fill($attributes);

        if (!empty($attributes['added_credit_transaction_id'])) {
            $credit->CREDIT_TRANSACTION_ID = $attributes['added_credit_transaction_id'];
        }

        if (!empty($attributes['added_user_id'])) {
            $credit->USER_ID = $attributes['added_user_id'];
        }

        if (!empty($attributes['added_requested_by'])) {
            $credit->CREDIT_REQUESTER = $attributes['added_requested_by'];
        }

        if (!empty($attributes['added_credit'])) {

            $credit->CREDIT_AMOUNT = $attributes['added_credit'];
            $credit->PREVIOUS_BALANCE = $attributes['added_user_credit'];

            $attributes['added_credit'] = $attributes['added_user_credit'] + $attributes['added_credit'];

            $this->updateUserCredit($attributes['added_user_id'],$attributes['added_credit']);

            $credit->CURRENT_BALANCE = $attributes['added_credit'];
        }

        if (!empty($attributes['added_price'])) {
            $credit->CREDIT_PRICE = $attributes['added_price'];
        }

        if (!empty($attributes['added_currency'])) {
            $credit->CURRENCY_CODE = $attributes['added_currency'];

            if (strlen($attributes['added_currency'])>3) { return false; }
        }

        if (!empty($attributes['added_payment_method'])) {
            $credit->PAYMENT_METHOD = $attributes['added_payment_method'];
        }
        
        if (!empty($attributes['added_information'])) {
            $credit->TRANSACTION_REMARK = $attributes['added_information'];
        }

        if(empty($credit->TRANSACTION_REF)){
            $month = date('m');
            $year = date('Y');
            $day = date('d');

            $ref = "T".$year.$month.$day.Str::random(5);
            $credit->TRANSACTION_REF = $ref;
        }

        if(empty($credit->CREATED_BY)){
            $credit->CREATED_BY = $this->user()->getKey();
        }

        if(empty($credit->CREATED_DATE)){
            $credit->CREATED_DATE = now();
        }

        $saved = $credit->save();return $saved;
    }

    /**
     * Deduct credit
     *
     * @param  array $attributes
     * @param Credit $credit
     * @return bool
     */
    public function deduct(array $attributes = [], Credit $credit = null)
    {
        $credit = $credit ?? $this->model();

        $credit->fill($attributes);

        if (!empty($attributes['added_credit_transaction_id'])) {
            $credit->CREDIT_TRANSACTION_ID = $attributes['added_credit_transaction_id'];
        }

        if (!empty($attributes['added_user_id'])) {
            $credit->USER_ID = $attributes['added_user_id'];
        }

        if (!empty($attributes['added_credit_deduct'])) {

            $credit->CREDIT_AMOUNT = $attributes['added_credit_deduct'];
            $credit->PREVIOUS_BALANCE = $attributes['added_user_credit'];

            $attributes['added_credit_deduct'] = $attributes['added_user_credit'] - $attributes['added_credit_deduct'];

            $this->updateUserCredit($attributes['added_user_id'],$attributes['added_credit_deduct']);

            $credit->CURRENT_BALANCE = $attributes['added_credit_deduct'];
        }
        
        if (!empty($attributes['added_information_deduct'])) {
            $credit->TRANSACTION_REMARK = $attributes['added_information_deduct'];
        }

        if(empty($credit->PAYMENT_METHOD)){
            $credit->PAYMENT_METHOD = 'unspecified';
        }

        if(empty($credit->CURRENCY_CODE)){
            $credit->CURRENCY_CODE = 'rp';
        }

        if(empty($credit->CREDIT_PRICE)){
            $credit->CREDIT_PRICE = 0;
        }

        if(empty($credit->CREDIT_REQUESTER)){
            $credit->CREDIT_REQUESTER = '-';
        }

        if(empty($credit->TRANSACTION_REF)){
            $month = date('m');
            $year = date('Y');
            $day = date('d');

            $ref = "T".$year.$month.$day.Str::random(5);
            $credit->TRANSACTION_REF = $ref;
        }

        if(empty($credit->CREATED_BY)){
            $credit->CREATED_BY = $this->user()->getKey();
        }

        if(empty($credit->CREATED_DATE)){
            $credit->CREATED_DATE = now();
        }

        $saved = $credit->save(); return $saved;
    }

    /**
     * Update Payment Acknowledgement
     *
     * @param  array $attributes
     * @param Credit $credit
     * @return bool
     */
    public function update_payment_acknowledgement(array $attributes = [], Credit $credit = null)
    {

        $updated = Credit::where('CREDIT_TRANSACTION_ID', $attributes['added_transaction_id'])
                    ->update([
                        'PAYMENT_DATE' => $attributes['payment_date_acknowledgement'],
                        'UPDATED_DATE' => now(),
                        'UPDATED_BY' => $this->user()->getKey()
                    ]);

        return $updated;
    }

    /**
     * Update Credit
     *
     * @param  array $attributes
     * @param Credit $credit
     * @return bool
     */
    public function update(array $attributes = [], Credit $credit = null)
    {

        if (strlen($attributes['edited_currency'])>3) { return false; }

        $updated = Credit::where('CREDIT_TRANSACTION_ID', $attributes['edited_credit_transaction_id'])
                    ->update([
                        'CREDIT_REQUESTER' => $attributes['edited_requested_by'],
                        'CREDIT_PRICE' => $attributes['edited_price'],
                        'CURRENCY_CODE' => $attributes['edited_currency'],
                        'PAYMENT_METHOD' =>$attributes['edited_payment_method'],
                        'TRANSACTION_REMARK' =>$attributes['edited_information'],
                        'UPDATED_DATE' => now(),
                        'UPDATED_BY' => $this->user()->getKey()
                    ]);

        return $updated;
    }

    /**
     * Update User Credit
     *
     * @param  array $input
     * @param User $User
     * @return bool
     */
    public function updateUserCredit($USER_ID,$CREDIT)
    {   
        $updated = ApiUser::where('USER_ID', $USER_ID)
                    ->update([
                        'CREDIT' => $CREDIT,
                        'UPDATED_DATE' => now(),
                        'UPDATED_BY' => $this->user()->getKey()
                    ]);

        return $updated;
    }

}
