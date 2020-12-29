<?php

namespace App\Repositories;

use App\Models\Invoice\InvoiceProduct;
use App\Models\Invoice\InvoiceHistory;
use App\Models\Invoice\InvoiceSetting;
use App\Repositories\ProfileRepositories;
use App\Repositories\Repository as RepositoryContract;
use Illuminate\Database\Eloquent\Builder;

class HistoryRepositories extends RepositoryContract
{

    /**
     * Create a new HistoryRepositories instance.
     *
     * @return void
     */
    function __construct(ProfileRepositories $profileRepo)
    {
        $this->model = $this->model();
        $this->profileRepo = $profileRepo;
    }

    /**
     * Get Model instance
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function model()
    {
        return $this->model ?? new InvoiceHistory;
    }

    /**
     * Get History Builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function builder()
    {
        $builder = $this->model()->query();
        return $builder;
    }

    /**
     * Get History data
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function data()
    {
        return $this->builder()->get();
    }

    /**
     * Get History data by profile id
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function dataByProfile($PROFILE_ID)
    {
        return InvoiceHistory::where('INVOICE_PROFILE_ID',$PROFILE_ID)->get();
    }

    /**
     * Get invoice data from history.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function invoiceDataHistory($HISTORY_ID)
    {
        return InvoiceHistory::where('INVOICE_HISTORY.INVOICE_HISTORY_ID',$HISTORY_ID)
                        ->join('INVOICE_PRODUCT', 'INVOICE_HISTORY.INVOICE_NUMBER', '=', 'INVOICE_PRODUCT.OWNER_ID')
                        ->where('INVOICE_PRODUCT.OWNER_TYPE',"HISTORY")
                        ->get();

    }

    /**
     * Get invoice data from profile.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function invoiceDataProfile($HISTORY_ID)
    {
        return InvoiceHistory::where('INVOICE_HISTORY.INVOICE_HISTORY_ID',$HISTORY_ID)
                        ->where('INVOICE_PRODUCT.OWNER_TYPE','PROFILE')
                        ->join('INVOICE_PROFILE', 'INVOICE_HISTORY.INVOICE_PROFILE_ID', '=', 'INVOICE_PROFILE.INVOICE_PROFILE_ID')
                        ->join('INVOICE_PRODUCT', 'INVOICE_PROFILE.INVOICE_PROFILE_ID', '=', 'INVOICE_PRODUCT.OWNER_ID')
                        ->join('CLIENT', 'INVOICE_PROFILE.CLIENT_ID', '=', 'CLIENT.CLIENT_ID')
                        ->get();
    }

    /**
     * Get profile data.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function profile($HISTORY_ID)
    {
        return InvoiceHistory::where('INVOICE_HISTORY.INVOICE_HISTORY_ID',$HISTORY_ID)
                        ->join('INVOICE_PROFILE', 'INVOICE_HISTORY.INVOICE_PROFILE_ID', '=', 'INVOICE_PROFILE.INVOICE_PROFILE_ID')
                        ->join('INVOICE_BANK', 'INVOICE_PROFILE.INVOICE_BANK_ID', '=', 'INVOICE_BANK.INVOICE_BANK_ID')
                        ->join('CLIENT', 'INVOICE_PROFILE.CLIENT_ID', '=', 'CLIENT.CLIENT_ID')
                        ->first();
    }

    /**
     * Get profile data.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function setting()
    {
        return InvoiceSetting::first();
    }

    /**
     * create invoice.
     *
     * @param  array $attributes
     * @param Bank $bank
     * @return bool
     */
    public function save(array $attributes = [], InvoiceHistory $history = null)
    {
        $history = $history ?? $this->model();

        $history->fill($attributes);

        if (!empty($attributes['added_invoice_history_id'])) {
            $history->INVOICE_HISTORY_ID = $attributes['added_invoice_history_id'];
        }

        if (!empty($attributes['added_invoice_profile_id'])) {
            $history->INVOICE_PROFILE_ID = $attributes['added_invoice_profile_id'];
        }

        if (!empty($attributes['added_invoice_number'])) {
            $history->INVOICE_NUMBER = $attributes['added_invoice_number'];
        }

        if (!empty($attributes['added_invoice_date'])) {
            $history->START_DATE = $attributes['added_invoice_date'];
        }

        if (!empty($attributes['added_due_date'])) {
            $history->DUE_DATE = $attributes['added_due_date'];
        }

        if (!empty($attributes['added_ref_number'])) {
            $history->REF_NUMBER = $attributes['added_ref_number'];
        }

        if (empty($attributes['added_status'])) {
            $history->STATUS = 0;
        }

        if (empty($attributes['added_invoice_type'])) {
            $history->INVOICE_TYPE = 'ORIGINAL';
        }

        if (empty($attributes['added_file_name'])) {

            $year = substr($attributes['added_invoice_date'], 0, 4);
            $month = substr($attributes['added_invoice_date'], 5, 2);

            $number = $attributes['added_invoice_number'];

            $invoiceProfileName = $this->profileRepo->joinedProfile($attributes['added_invoice_profile_id']);

            $name = str_replace(' ', '_', $invoiceProfileName->COMPANY_NAME);
            $monthName = date("F", mktime(0, 0, 0, $month, 10)); 

            $fileName = $year.'/'.$month.'/'.$number.'_'.$name.'_'.$monthName.'_ORIGINAL_PREVIEW.pdf';

            $history->FILE_NAME = $fileName;

        }

        if(empty($history->CREATED_AT)){
            $history->CREATED_AT = now();
        }

        $saved = $history->save();

        $settings = InvoiceSetting::first();

        $LAST_INVOICE_NUMBER = $attributes['added_invoice_number']+1;
        
        InvoiceSetting::where('SETTING_ID',$settings->SETTING_ID)
                    ->update([
                        'LAST_INVOICE_NUMBER'=> $LAST_INVOICE_NUMBER
                    ]);

        return $history->INVOICE_HISTORY_ID;
    }

    /**
     * copy invoice.
     *
     * @param  array $attributes
     * @return bool
     */
    public function copy($HISTORY_ID)
    {
        $historyDetail = InvoiceHistory::where('INVOICE_HISTORY_ID', $HISTORY_ID)->first();

        $history = $history ?? $this->model();

        $history->INVOICE_PROFILE_ID = $historyDetail['INVOICE_PROFILE_ID'];

        $history->INVOICE_NUMBER = $historyDetail['INVOICE_NUMBER'];

        $history->START_DATE = $historyDetail['START_DATE'];

        $history->DUE_DATE = $historyDetail['DUE_DATE'];

        $history->REF_NUMBER = $historyDetail['REF_NUMBER'];

        $history->STATUS = 1;

        $history->INVOICE_TYPE = 'COPIED';

        /**
         * Naming Copied Invoice
         */
            $year = substr($historyDetail['START_DATE'], 0, 4);
            $month = substr($historyDetail['START_DATE'], 5, 2);

            $number = $historyDetail['INVOICE_NUMBER'];

            $invoiceProfileName = $this->profileRepo->joinedProfile($historyDetail['INVOICE_PROFILE_ID']);

            $name = str_replace(' ', '_', $invoiceProfileName->COMPANY_NAME);
            $monthName = date("F", mktime(0, 0, 0, $month, 10)); 

            $fileName = $year.'/'.$month.'/'.$number.'_'.$name.'_'.$monthName.'_COPIED_PREVIEW.pdf';

            $history->FILE_NAME = $fileName;

        $history->CREATED_AT = now();
        
        /**
         * Create Copy
         */

        $copied = $history->save();

        return $copied;
    }

    /**
     * revise invoice.
     *
     * @param  array $attributes
     * @return bool
     */
    public function revise($HISTORY_ID)
    {
        $historyDetail = InvoiceHistory::where('INVOICE_HISTORY_ID', $HISTORY_ID)->first();

        $history = $history ?? $this->model();

        $history->INVOICE_PROFILE_ID = $historyDetail['INVOICE_PROFILE_ID'];

        $history->INVOICE_NUMBER = $historyDetail['INVOICE_NUMBER'];

        $history->START_DATE = $historyDetail['START_DATE'];

        $history->DUE_DATE = $historyDetail['DUE_DATE'];

        $history->REF_NUMBER = $historyDetail['REF_NUMBER'];

        $history->STATUS = 0;

        $history->INVOICE_TYPE = 'REVISED';

        /**
         * Naming Revised Invoice
         */
            $year = substr($historyDetail['START_DATE'], 0, 4);
            $month = substr($historyDetail['START_DATE'], 5, 2);

            $number = $historyDetail['INVOICE_NUMBER'];

            $invoiceProfileName = $this->profileRepo->joinedProfile($historyDetail['INVOICE_PROFILE_ID']);

            $name = str_replace(' ', '_', $invoiceProfileName->COMPANY_NAME);
            $monthName = date("F", mktime(0, 0, 0, $month, 10)); 

            $fileName = $year.'/'.$month.'/'.$number.'_'.$name.'_'.$monthName.'_REVISED_PREVIEW.pdf';

            $history->FILE_NAME = $fileName;

        $history->CREATED_AT = now();
        
        /**
         * Create Revised
         */

        $revised = $history->save();

        $revised = $history->INVOICE_HISTORY_ID;

        return $revised;
    }

    /**
     * Update history
     *
     * @param  array $attributes
     * @param History $history
     * @return bool
     */
    public function update(array $attributes = [])
    {
        $updated = 
            InvoiceHistory::where('INVOICE_HISTORY_ID', $attributes["edited_invoice_history_id"])
                ->update([
                    'INVOICE_NUMBER' => $attributes["edited_invoice_number"], 
                    'START_DATE' => $attributes["edited_invoice_date"],
                    'DUE_DATE' => $attributes["edited_due_date"],
                    'REF_NUMBER' => $attributes['edited_ref_number'],
                    'UPDATED_AT' => now()
                ]);

        return $updated;
    }

    /**
     * Update owner type
     *
     * @param  array $attributes
     * @param History $history
     * @return bool
     */
    public function updateOwnerType($HISTORY_ID)
    {
        $all_products = $this->invoiceDataProfile($HISTORY_ID);

        foreach($all_products as $product){

            InvoiceProduct::where('PRODUCT_ID', $product->PRODUCT_ID)
                ->update([
                    'OWNER_TYPE' => 'HISTORY', 
                    'OWNER_ID' => $product->INVOICE_NUMBER
                ]);
            
            $products = new InvoiceProduct;

            $products->PRODUCT_NAME = $product->PRODUCT_NAME;

            $products->PERIOD = $product->PERIOD;

            $products->IS_PERIOD = $product->IS_PERIOD;

            $products->UNIT_PRICE = $product->UNIT_PRICE;

            $products->QTY = $product->QTY;

            $products->USE_REPORT = $product->USE_REPORT;

            $products->REPORT_NAME = $product->REPORT_NAME;

            $products->OPERATOR = $product->OPERATOR;

            $products->OWNER_TYPE = $product->OWNER_TYPE;

            $products->OWNER_ID = $product->OWNER_ID;

            $products->save();
        }

        return true;
    }

    /**
     * Lock history
     *
     */
    public function lock($HISTORY_ID)
    {
        $lock = 
            InvoiceHistory::where('INVOICE_HISTORY_ID', $HISTORY_ID)
                ->update([
                    'STATUS' => 1, 
                    'INVOICE_TYPE' => 'FINAL',
                    'LOCKED_AT' => now()
                ]);

        return $lock;
    }
    
    /**
     * Remove the History from database.
     *
     * @param  int  $HISTORY_ID
     * @return bool
     */
    public function delete($HISTORY_ID)
    {
        $history=InvoiceHistory::where('INVOICE_HISTORY_ID',$HISTORY_ID)->delete();

        return (bool) $history;
    }

}
