<?php

namespace App\Repositories;

use App\Models\Operator;
use App\Repositories\Repository as RepositoryContract;
use Illuminate\Database\Eloquent\Builder;

class OperatorRepositories extends RepositoryContract
{

    /**
     * Create a new OperatorRepository instance.
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
        return $this->model ?? new Operator;
    }

    /**
     * Get Operator Builder
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
    public function data()
    {
        return $this->builder()->groupBy('OPERATOR_NAME')->get();
    }
    
    /**
     * Get Search Data
     * @return
     */
    public function searchData($search)
    {
        return Operator::where('OPERATOR_NAME', 'like', '%' .$search . '%')->get();
    }

}
