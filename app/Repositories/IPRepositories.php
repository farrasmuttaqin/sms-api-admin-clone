<?php

namespace App\Repositories;

use App\Models\IPRestrictions;
use App\Repositories\Repository as RepositoryContract;
use Illuminate\Database\Eloquent\Builder;

class IPRepositories extends RepositoryContract
{

    /**
     * Create a new IPRepository instance.
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
        return $this->model ?? new IPRestrictions;
    }

    /**
     * Get IP Address Builder
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
        return IPRestrictions::where('USER_ID',$USER_ID)->get();
    }

    /**
     * Store new ip address to database
     *
     * @param  array $attributes
     * @param IPRestrictions $ipAddress
     * @return bool
     */
    public function save(array $attributes = [], IPRestrictions $ipAddress = null)
    {
        $ipAddress = $ipAddress ?? $this->model();

        $ipAddress->fill($attributes);

        if (!empty($attributes['added_ip_id'])) {
            $ipAddress->USER_IP_ID = $attributes['added_ip_id'];
        }

        if (!empty($attributes['added_user_id'])) {
            $ipAddress->USER_ID = $attributes['added_user_id'];
        }

        if (!empty($attributes['added_ip_address'])) {
            $ipAddress->IP_ADDRESS = $attributes['added_ip_address'];
        }

        $saved = $ipAddress->save();

        return $saved;
    }
    
    /**
     * Remove the IP Address from database.
     *
     * @param  int  $IP_ID
     * @return bool
     */
    public function delete($IP_ID)
    {
        $ipAddress=IPRestrictions::where('USER_IP_ID',$IP_ID)->delete();

        return (bool) $ipAddress;
    }

}
