<?php

namespace App\Repositories;

use App\Models\Client;
use App\Models\ApiUser;
use App\Models\Invoice\InvoiceProduct;
use App\Repositories\Repository as RepositoryContract;
use Illuminate\Database\Eloquent\Builder;

class ProductRepositories extends RepositoryContract
{

    /**
     * Create a new ProductRepositories instance.
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
        return $this->model ?? new InvoiceProduct;
    }

    /**
     * Get product Builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function builder()
    {
        $builder = $this->model()->query();
        return $builder;
    }

    /**
     * Get product data
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function data()
    {
        return $this->builder()->get();
    }

    /**
     * Get Product
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getProducts($PROFILE_ID)
    {
        return InvoiceProduct::where('OWNER_ID',$PROFILE_ID)
                        ->get();
    }

    /**
     * Store new bank to database
     *
     * @param  array $attributes
     * @param InvoiceProduct $product
     * @return bool
     */
    public function save(array $attributes = [], InvoiceProduct $product = null)
    {
        $product = $product ?? $this->model();

        $product->fill($attributes);

        if (!empty($attributes['added_product_id'])) {
            $product->PRODUCT_ID = $attributes['added_product_id'];
        }

        if (!empty($attributes['added_product_name'])) {
            $product->PRODUCT_NAME = $attributes['added_product_name'];
        }

        if(empty($product->PERIOD)){
            $product->PERIOD = now();
        }

        if (!empty($attributes['added_use_period'])) {
            $product->IS_PERIOD = (int)$attributes['added_use_period'];
        }else{
            $product->IS_PERIOD = 0;
        }

        if (!empty($attributes['added_unit_price'])) {
            $product->UNIT_PRICE = $attributes['added_unit_price'];
        }

        if (!empty($attributes['added_quantity'])) {
            $product->QTY = $attributes['added_quantity'];
        }

        if (!empty($attributes['added_use_report'])) {
            $product->USE_REPORT = (int)$attributes['added_use_report'];
        }else{
            $product->USE_REPORT = 0;
        }

        if (!empty($attributes['added_report_name'])) {
            $product->REPORT_NAME = $attributes['added_report_name'];
        }

        if (!empty($attributes['added_operator'])) {
            $product->OPERATOR = $attributes['added_operator'];
        }

        if (!empty($attributes['added_owner_type'])) {
            $product->OWNER_TYPE = $attributes['added_owner_type'];
        }

        if (!empty($attributes['added_owner_id'])) {
            $product->OWNER_ID = $attributes['added_owner_id'];
        }

        $saved = $product->save();

        return $saved;
    }

    /**
     * Update product
     *
     * @param  array $attributes
     * @param InvoiceProduct $product
     * @return bool
     */
    public function update(array $attributes = [])
    {   
        if (empty($attributes['edited_use_report'])){
            $attributes['edited_use_report'] = 0;
        }
        
        $updated = 
            InvoiceProduct::where('PRODUCT_ID', $attributes["edited_product_id"])
                ->update([
                    'PRODUCT_NAME' => $attributes["edited_product_name"],
                    'PERIOD' => now(),
                    'IS_PERIOD' => $attributes["edited_use_period"],
                    'UNIT_PRICE' => $attributes["edited_unit_price"],
                    'QTY' => $attributes["edited_quantity"],
                    'USE_REPORT' => $attributes['edited_use_report'],
                    'REPORT_NAME' => $attributes['edited_report_name'],
                    'OPERATOR' => $attributes['edited_operator'],
                    'OWNER_TYPE' => $attributes['edited_owner_type'],
                    'OWNER_ID' => $attributes['edited_owner_id'],
                ]);

        return $updated;
    }

    /**
     * Remove the PRODUCT_ID from database.
     *
     * @param  int  $PRODUCT_ID
     * @return bool
     */
    public function delete($PRODUCT_ID)
    {
        $product=InvoiceProduct::where('PRODUCT_ID',$PRODUCT_ID)->delete();

        return (bool) $product;
    }
}
