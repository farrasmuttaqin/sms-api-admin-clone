<?php

namespace App\Repositories;

use App\Models\VirtualNumber;
use App\Repositories\Repository as RepositoryContract;
use Illuminate\Database\Eloquent\Builder;

class VNRepositories extends RepositoryContract
{

    /**
     * Create a new virtual number instance.
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
        return $this->model ?? new VirtualNumber;
    }

    /**
     * Get virtual number Builder
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
        return VirtualNumber::where('USER_ID',$USER_ID)->get();
    }

    /**
     * Store new virtual number to database
     *
     * @param  array $attributes
     * @param VirtualNumber $vn
     * @return bool
     */
    public function save(array $attributes = [], VirtualNumber $vn = null)
    {
        $vn = $vn ?? $this->model();

        $vn->fill($attributes);

        if (!empty($attributes['added_virtual_number_id'])) {
            $vn->VIRTUAL_NUMBER_ID = $attributes['added_virtual_number_id'];
        }

        if (!empty($attributes['added_user_id'])) {
            $vn->USER_ID = $attributes['added_user_id'];
        }

        if (!empty($attributes['added_destination'])) {
            $vn->DESTINATION = $attributes['added_destination'];
        }

        if ($attributes['added_use_forward_url'] == 0){
            $vn->URL_ACTIVE = (int)$attributes['added_use_forward_url'];
        }else if (!empty($attributes['added_use_forward_url'])) {
            $vn->URL_ACTIVE = (int)$attributes['added_use_forward_url'];
        }

        if (!empty($attributes['added_forward_url'])) {
            $vn->FORWARD_URL = $attributes['added_forward_url'];
        }

        $saved = $vn->save();

        return $saved;
    }

    /**
     * Update vn
     *
     * @param  array $attributes
     * @return bool
     */
    public function update(array $attributes = [])
    {
        $updated = 
            VirtualNumber::where('VIRTUAL_NUMBER_ID', $attributes["edited_virtual_id"])
                ->update([
                    'DESTINATION' => $attributes["edited_virtual_destination"], 
                    'USER_ID' => $attributes["USER_ID"],
                    'URL_ACTIVE' => (int)$attributes["edited_forward"],
                    'FORWARD_URL' =>$attributes["edited_URL"]
                ]);

        return $updated;
    }
    
    /**
     * Remove the Virtual Number from database.
     *
     * @param  int  $VN_ID
     * @return bool
     */
    public function delete($VN_ID)
    {
        $vn=VirtualNumber::where('VIRTUAL_NUMBER_ID',$VN_ID)->delete();

        return (bool) $vn;
    }

    

}
