<?php

namespace App\Repositories;

use App\Models\Cobrander;
use App\Models\ApiUser;
use App\Models\Client;
use App\Repositories\Repository as RepositoryContract;
use Illuminate\Database\Eloquent\Builder;

class ApiUserRepositories extends RepositoryContract
{

    /**
     * Create a new ApiUserRepositories instance.
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
        return $this->model ?? new ApiUser;
    }

    /**
     * Get User Builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function builder()
    {
        $builder = $this->model()->query();
        return $builder;
    }

    /**
     * Get User data
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function data()
    {
        return $this->builder()->get();
    }

    /**
     * Get User data (Distinct in Cobrander)
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function userData()
    {
        $cobranderNames = Cobrander::get();
        $cName = [];

        foreach($cobranderNames as $cobrander){
            $cName[] = $cobrander->COBRANDER_NAME;
        }

        return ApiUser::whereNotIn('USER.USER_NAME', $cName)
                        ->get();
    }

    /**
     * Get User joined data
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function allJoinedData()
    {
        return ApiUser::join(env('DB_DATABASE_SMS_API_ADMIN').'.ADMIN', 'USER.CREATED_BY', '=', env('DB_DATABASE_SMS_API_ADMIN').'.ADMIN.ADMIN_ID')
            ->join('CLIENT', 'CLIENT.CLIENT_ID', '=', 'USER.CLIENT_ID')
            ->groupBy('USER.USER_ID')
            ->get();
    }

    /**
     * Get User joined data
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function allJoinedData_detailUser($CLIENT_ID)
    {
        return ApiUser::where('CLIENT.CLIENT_ID',$CLIENT_ID)
            ->join(env('DB_DATABASE_SMS_API_ADMIN').'.ADMIN', 'USER.CREATED_BY', '=', env('DB_DATABASE_SMS_API_ADMIN').'.ADMIN.ADMIN_ID')
            ->join('CLIENT', 'CLIENT.CLIENT_ID', '=', 'USER.CLIENT_ID')
            ->groupBy('USER.USER_ID')
            ->get();
    }

    /**
     * Get Company Name
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function companyName($CLIENT_ID)
    {
        return Client::where('CLIENT_ID',$CLIENT_ID)->first();
    }

    /**
     * Get User detail data
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function userDetail($USER_ID)
    {
        $detail = ApiUser::where('USER_ID',$USER_ID)
            ->join(env('DB_DATABASE_SMS_API_ADMIN').'.ADMIN', 'USER.CREATED_BY', '=', env('DB_DATABASE_SMS_API_ADMIN').'.ADMIN.ADMIN_ID')
            ->join('CLIENT', 'CLIENT.CLIENT_ID', '=', 'USER.CLIENT_ID')
            ->join(env('DB_DATABASE_BILL_U_MESSAGE').'.COBRANDER', 'USER.COBRANDER_ID', '=', env('DB_DATABASE_BILL_U_MESSAGE').'.COBRANDER.COBRANDER_NAME')
            ->join('COUNTRY', 'CLIENT.COUNTRY_CODE', '=', 'COUNTRY.COUNTRY_CODE')
            ->first();

        if (!$detail){
            $detail = ApiUser::where('USER_ID',$USER_ID)
                ->join(env('DB_DATABASE_SMS_API_ADMIN').'.ADMIN', 'USER.CREATED_BY', '=', env('DB_DATABASE_SMS_API_ADMIN').'.ADMIN.ADMIN_ID')
                ->join('CLIENT', 'CLIENT.CLIENT_ID', '=', 'USER.CLIENT_ID')
                ->join('COUNTRY', 'CLIENT.COUNTRY_CODE', '=', 'COUNTRY.COUNTRY_CODE')
                ->first();
        }

        return $detail;
    }

    /**
     * Store new User to database
     *
     * @param  array $attributes
     * @param Agent $agent
     * @return bool
     */
    public function save(array $attributes = [], ApiUser $user = null)
    {
        $user = $user ?? $this->model();

        $user->fill($attributes);

        if (!empty($attributes['added_user_id'])) {
            $user->USER_ID = $attributes['added_user_id'];
        }

        if (!empty($attributes['added_client'])) {
            $user->CLIENT_ID = $attributes['added_client'];
        }

        if (!empty($attributes['added_username'])) {
            $user->USER_NAME = $attributes['added_username'];
        }

        if (!empty($attributes['added_delivery_url'])) {
            $user->DELIVERY_STATUS_URL = $attributes['added_delivery_url'];
        }

        if (!empty($attributes['added_password'])) {
            $user->PASSWORD = $attributes['added_password'];
        }

        if (!empty($attributes['added_cobrander'])) {
            $user->COBRANDER_ID = $attributes['added_cobrander'];
        }

        if ($attributes['added_user_activate'] == 0){
            $user->ACTIVE = (int)$attributes['added_user_activate'];
        }else if (!empty($attributes['added_user_activate'])) {
            $user->ACTIVE = (int)$attributes['added_user_activate'];
        }

        if ($attributes['added_status_delivery'] == 0){
            $user->URL_ACTIVE = (int)$attributes['added_status_delivery'];
        }else if (!empty($attributes['added_status_delivery'])) {
            $user->URL_ACTIVE = (int)$attributes['added_status_delivery'];
        }

        if ($attributes['added_is_postpaid'] == 0){
            $user->IS_POSTPAID = (int)$attributes['added_is_postpaid'];
        }else if (!empty($attributes['added_is_postpaid'])) {
            $user->IS_POSTPAID = (int)$attributes['added_is_postpaid'];
        }

        if ($attributes['added_is_bl'] == 0){
            $user->USE_BLACKLIST = (int)$attributes['added_is_bl'];
        }else if (!empty($attributes['added_is_bl'])) {
            $user->USE_BLACKLIST = (int)$attributes['added_is_bl'];
        }

        if ($attributes['added_isojk'] == 0){
            $user->IS_OJK = (int)$attributes['added_isojk'];
        }else if (!empty($attributes['added_isojk'])) {
            $user->IS_OJK = (int)$attributes['added_isojk'];
        }

        if(empty($user->CREATED_BY)){
            $user->CREATED_BY = $this->user()->getKey();
        }

        if(empty($user->CREATED_AT)){
            $user->CREATED_DATE = now();
        }

        if(empty($user->version)){
            $user->version = 0;
        }

        if(empty($user->CREDIT)){
            $user->CREDIT = 0;
        }

        $saved = $user->save();

        return $saved;
    }

    /**
     * Update User
     *
     * @param  array $attributes
     * @param User $User
     * @return bool
     */
    public function update(array $attributes = [])
    {   
        $updated = 
            ApiUser::where('USER_ID', $attributes["edited_user_id"])
                ->update([
                    'CLIENT_ID' => $attributes["edited_client_id"],
                    'COBRANDER_ID' => $attributes["edited_cobrander_id"],
                    'ACTIVE' => (int)$attributes["edited_user_activate"],
                    'URL_ACTIVE' => (int)$attributes["edited_status_delivery"],
                    'DELIVERY_STATUS_URL' => $attributes["edited_delivery_url"],
                    'IS_POSTPAID' => (int)$attributes["edited_is_postpaid"],
                    'IS_OJK' => (int)$attributes["edited_isojk"],
                    'USE_BLACKLIST' => (int)$attributes["edited_is_bl"],
                    'UPDATED_DATE' => now(),
                    'UPDATED_BY' => $this->user()->getKey()
                ]);

        return $updated;
    }

    /**
     * Update User Password
     *
     * @param  array $attributes
     * @param User $User
     * @return bool
     */
    public function updatePassword(array $attributes = [])
    {
        $updated = 
            ApiUser::where('USER_ID', $attributes["edited_user_id"])
                ->update([
                    'PASSWORD' => $attributes["edited_user_password"]
                ]);

        return $updated;
    }

    /**
     * Change the User Status from database.
     *
     * @param  int  $USER_ID, $ACTIVE
     * @return bool
     */
    public function changeStatus($USER_ID, $ACTIVE)
    {
        /**
         * Change Status
         */

        if ($ACTIVE == 0){
            $ACTIVE = 1;
        }else $ACTIVE = 0;

        $changeStatus = 
            ApiUser::where('USER_ID', $USER_ID)
                ->update([
                    'ACTIVE' => $ACTIVE,
                    'UPDATED_DATE' => now(),
                    'UPDATED_BY' => $this->user()->getKey()
                ]);

        return $changeStatus;
    }

}
