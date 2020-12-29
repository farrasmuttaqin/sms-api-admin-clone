<?php

namespace App\Repositories;

use App\Models\Invoice\InvoiceBank;
use App\Repositories\Repository as RepositoryContract;
use Illuminate\Database\Eloquent\Builder;

class BankRepositories extends RepositoryContract
{

    /**
     * Create a new BankRepository instance.
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
        return $this->model ?? new InvoiceBank;
    }

    /**
     * Get Bank Builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function builder()
    {
        $builder = $this->model()->query();
        return $builder;
    }

    /**
     * Get Bank data
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function data()
    {
        return $this->builder()->get();
    }

    /**
     * Store new bank to database
     *
     * @param  array $attributes
     * @param Bank $bank
     * @return bool
     */
    public function save(array $attributes = [], Agent $agent = null)
    {
        $bank = $bank ?? $this->model();

        $bank->fill($attributes);

        if (!empty($attributes['added_bank_id'])) {
            $bank->INVOICE_BANK_ID = $attributes['added_bank_id'];
        }

        if (!empty($attributes['added_bank_name'])) {
            $bank->BANK_NAME = $attributes['added_bank_name'];
        }

        if (!empty($attributes['added_address'])) {
            $bank->ADDRESS = $attributes['added_address'];
        }

        if (!empty($attributes['added_account_name'])) {
            $bank->ACCOUNT_NAME = $attributes['added_account_name'];
        }

        if (!empty($attributes['added_account_number'])) {
            $bank->ACCOUNT_NUMBER = $attributes['added_account_number'];
        }

        $saved = $bank->save();

        return $saved;
    }

    /**
     * Update bank
     *
     * @param  array $attributes
     * @param Bank $bank
     * @return bool
     */
    public function update(array $attributes = [])
    {
        $updated = 
            InvoiceBank::where('INVOICE_BANK_ID', $attributes["edited_bank_id"])
                ->update([
                    'BANK_NAME' => $attributes["edited_bank_name"], 
                    'ADDRESS' => $attributes["edited_address"],
                    'ACCOUNT_NAME' => $attributes["edited_account_name"],
                    'ACCOUNT_NUMBER' => $attributes["edited_account_number"]
                ]);

        return $updated;
    }
    
    /**
     * Remove the Bank from database.
     *
     * @param  int  $BANK_ID
     * @return bool
     */
    public function delete($BANK_ID)
    {
        $bank=InvoiceBank::where('INVOICE_BANK_ID',$BANK_ID)->delete();

        return (bool) $bank;
    }

}
