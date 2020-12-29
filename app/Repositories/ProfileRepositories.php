<?php

namespace App\Repositories;

use App\Models\Client;
use App\Models\ApiUser;
use App\Models\Invoice\InvoiceProfile;
use App\Repositories\Repository as RepositoryContract;
use Illuminate\Database\Eloquent\Builder;

class ProfileRepositories extends RepositoryContract
{

    /**
     * Create a new ProfileRepositories instance.
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
        return $this->model ?? new InvoiceProfile;
    }

    /**
     * Get profile Builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function builder()
    {
        $builder = $this->model()->query();
        return $builder;
    }

    /**
     * Get profile data
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function data()
    {
        return $this->builder()->get();
    }

    /**
     * Get profile allJoinedData
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function allJoinedData()
    {
        return InvoiceProfile::join('CLIENT', 'INVOICE_PROFILE.CLIENT_ID', '=', 'CLIENT.CLIENT_ID')
                        ->join('INVOICE_BANK', 'INVOICE_PROFILE.INVOICE_BANK_ID', '=', 'INVOICE_BANK.INVOICE_BANK_ID')
                        ->get();
    }

    /**
     * Get join data profile
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function joinedProfile($PROFILE_ID)
    {
        return InvoiceProfile::where('INVOICE_PROFILE_ID',$PROFILE_ID)
                    ->join('CLIENT', 'INVOICE_PROFILE.CLIENT_ID', '=', 'CLIENT.CLIENT_ID')
                    ->join('INVOICE_BANK', 'INVOICE_PROFILE.INVOICE_BANK_ID', '=', 'INVOICE_BANK.INVOICE_BANK_ID')
                    ->first();
    }

    /**
     * Get user groups
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function groups()
    {
        return ApiUser::whereNotNull('USER.BILLING_REPORT_GROUP_ID')
                    ->whereNotNull('USER.BILLING_PROFILE_ID')
                    ->join(env('DB_DATABASE_BILL_PRICELIST').'.BILLING_REPORT_GROUP'
                        , 'USER.BILLING_REPORT_GROUP_ID'
                        , '='
                        , env('DB_DATABASE_BILL_PRICELIST').'.BILLING_REPORT_GROUP.BILLING_REPORT_GROUP_ID')
                    ->get();
    }

    /**
     * Get user report
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function reports()
    {
        return ApiUser::whereNotNull('BILLING_PROFILE_ID')
                    ->whereNull('BILLING_REPORT_GROUP_ID')
                    ->get();
    }

    /**
     * Get join data profile
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function users($CLIENT_ID)
    {
        $users = ApiUser::where('CLIENT_ID',$CLIENT_ID)->get();
        $data = [];

        foreach($users as $user){
            $data[] = $user->USER_NAME;
        }

        $user = implode(", ",$data);

        return $user;
    }

    /**
     * Get join data client
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function allJoinedDataClient()
    {
        $Client_ID = InvoiceProfile::pluck('CLIENT_ID')->all();

        return Client:: whereNotIn('CLIENT_ID', $Client_ID)
                        ->join('ADMIN', 'CLIENT.CREATED_BY', '=', 'ADMIN.ADMIN_ID')
                        ->join('COUNTRY', 'CLIENT.COUNTRY_CODE', '=', 'COUNTRY.COUNTRY_CODE')
                        ->get();
    }

    /**
     * Store new bank to database
     *
     * @param  array $attributes
     * @param InvoiceProfile $profile
     * @return bool
     */
    public function save(array $attributes = [], InvoiceProfile $profile = null)
    {
        $profile = $profile ?? $this->model();

        $profile->fill($attributes);

        if (!empty($attributes['added_profile_id'])) {
            $profile->INVOICE_PROFILE_ID = $attributes['added_profile_id'];
        }

        if (!empty($attributes['added_client'])) {
            $profile->CLIENT_ID = $attributes['added_client'];
        }

        if (!empty($attributes['added_payment_detail'])) {
            $profile->INVOICE_BANK_ID = $attributes['added_payment_detail'];
        }

        if (!empty($attributes['added_auto_generate'])) {
            $profile->AUTO_GENERATE = $attributes['added_auto_generate'];
        }

        if (!empty($attributes['added_approved_name'])) {
            $profile->APPROVED_NAME = $attributes['added_approved_name'];
        }

        if (!empty($attributes['added_approved_position'])) {
            $profile->APPROVED_POSITION = $attributes['added_approved_position'];
        }

        if(empty($profile->CREATED_AT)){
            $profile->CREATED_AT = now();
        }

        $saved = $profile->save();

        return $saved;
    }

    /**
     * Update profile
     *
     * @param  array $attributes
     * @param InvoiceProfile $profile
     * @return bool
     */
    public function update(array $attributes = [])
    {
        $updated = 
            InvoiceProfile::where('INVOICE_PROFILE_ID', $attributes["edited_profile_id"])
                ->update([
                    'INVOICE_BANK_ID' => $attributes["edited_payment_detail"],
                    'AUTO_GENERATE' => $attributes["edited_auto_generate"],
                    'APPROVED_NAME' => $attributes["edited_approved_name"],
                    'APPROVED_POSITION' => $attributes["edited_approved_position"],
                    'UPDATED_AT' => now()
                ]);

        return $updated;
    }
}
