<?php

namespace App\Repositories;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;

abstract class Repository
{

    /**
     * Authenticated user
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $user;

    /**
     * Model that will process
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * Get Model instance
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    abstract public function model();

    /**
     * Get Model Builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    abstract public function builder();

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    public function user()
    {
        return auth()->user() ?? $this->deny();
    }

}
