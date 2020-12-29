<?php

namespace App\Repositories;

use App\Models\Client;
use App\Repositories\Repository as RepositoryContract;
use Illuminate\Database\Eloquent\Builder;

class ClientRepositories extends RepositoryContract
{

    /**
     * Create a new ClientRepository instance.
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
        return $this->model ?? new Client;
    }

    /**
     * Get Client Builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function builder()
    {
        $builder = $this->model()->query();
        return $builder;
    }

    /**
     * Get client data
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function data()
    {
        return $this->builder()->get();
    }

    /**
     * Get join data with country table and admin(user) table
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function allJoinedData()
    {
        return Client::join('ADMIN', 'CLIENT.CREATED_BY', '=', 'ADMIN.ADMIN_ID')
                        ->join('COUNTRY', 'CLIENT.COUNTRY_CODE', '=', 'COUNTRY.COUNTRY_CODE')
                        ->get();
    }

    /**
     * Store new client to database
     *
     * @param  array $attributes
     * @param User $client
     * @return bool
     */
    public function save(array $attributes = [], Client $client = null)
    {
        $client = $client ?? $this->model();

        $client->fill($attributes);

        if (!empty($attributes['added_client_id'])) {
            $client->CLIENT_ID = $attributes['added_client_id'];
        }

        if (!empty($attributes['added_company_name'])) {
            $client->COMPANY_NAME = $attributes['added_company_name'];
        }

        if (!empty($attributes['added_company_url'])) {
            $client->COMPANY_URL = $attributes['added_company_url'];
        }

        if (!empty($attributes['added_country'])) {
            $client->COUNTRY_CODE = $attributes['added_country'];
        }

        if (!empty($attributes['added_contact_name'])) {
            $client->CONTACT_NAME = $attributes['added_contact_name'];
        }
        
        if (!empty($attributes['added_contact_email'])) {
            $client->CONTACT_EMAIL = $attributes['added_contact_email'];
        }

        if (!empty($attributes['added_contact_phone'])) {
            $client->CONTACT_PHONE = $attributes['added_contact_phone'];
        }

        if (!empty($attributes['added_customer_id'])) {
            $client->CUSTOMER_ID = $attributes['added_customer_id'];
        }

        if (!empty($attributes['added_contact_address'])) {
            $client->CONTACT_ADDRESS = $attributes['added_contact_address'];
        }

        if(empty($client->CREATED_BY)){
            $client->CREATED_BY = $this->user()->getKey();
        }

        if(empty($client->CREATED_AT)){
            $client->CREATED_AT = now();
        }

        $saved = $client->save();

        return $saved;
    }
    
    /**
     * Update User
     *
     * @param  array $input
     * @param User $User
     * @return bool
     */
    public function update(array $input = [])
    {   
        $updated = Client::where('CLIENT_ID', $input["clientEditID"])
                    ->update([
                        'COMPANY_NAME' => $input["edited_company_name"], 
                        'COMPANY_URL' => $input["edited_company_url"],
                        'COUNTRY_CODE' => $input["edited_country"],
                        'CONTACT_NAME' => $input["edited_contact_name"],
                        'CONTACT_EMAIL' => $input["edited_contact_email"],
                        'CONTACT_PHONE' => $input["edited_contact_phone"],
                        'CONTACT_ADDRESS' => $input["edited_contact_address"],
                        'UPDATED_AT' => now(),
                        'UPDATED_BY' => $this->user()->getKey(),
                        'CUSTOMER_ID' => $input["edited_customer_id"]
                    ]);

        return $updated;
    }

    /**
     * Remove the client from database.
     *
     * @param  int  $CLIENT_ID
     * @return bool
     */
    public function delete($CLIENT_ID)
    {
        $client=Client::where('CLIENT_ID',$CLIENT_ID)->delete();

        return (bool) $client;
    }

    /**
     * Change the archived status of client.
     *
     */
    public function changeArchivedStatus($CLIENT_ID, $ARCHIVED)
    {
        /**
         * Change Status
         */

        if ($ARCHIVED == null){
            $date = now();
        }else $date = null;

        $changeStatus = 
            Client::where('CLIENT_ID', $CLIENT_ID)
                ->update([
                    'ARCHIVED_DATE' => $date,
                    'UPDATED_AT' => now(),
                    'UPDATED_BY' => $this->user()->getKey()
                ]);

        return $changeStatus;
    }

}
