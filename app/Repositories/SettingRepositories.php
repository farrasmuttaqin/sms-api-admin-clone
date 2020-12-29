<?php

namespace App\Repositories;

use App\Models\Invoice\InvoiceSetting;
use App\Repositories\Repository as RepositoryContract;
use Illuminate\Database\Eloquent\Builder;

class SettingRepositories extends RepositoryContract
{

    /**
     * Create a new SettingRepositories instance.
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
        return $this->model ?? new InvoiceSetting;
    }

    /**
     * Get Setting Builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function builder()
    {
        $builder = $this->model()->query();
        return $builder;
    }

    /**
     * Get Setting data
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function data()
    {
        if ($this->builder()->get()== "[]"){

            $data[] = [
                'PAYMENT_PERIOD' => '14',
                'AUTHORIZED_NAME' => 'Mona Eftarina',
                'AUTHORIZED_POSITION' => 'Finance & Accounting Manager',
                'NOTE_MESSAGE' => "Please quote the above invoice number reference on all payment orders and note that all associated charges for the financial transfer are at the payees expense.<br>Any errors/discrepancies must be reported to PT. FIRST WAP INTERNATIONAL (financial@1rstwap.com) in writing withing 7 (seven) days, otherwise claims for changes will not be accepted",
                'INVOICE_NUMBER_PREFIX' => '1rstwap - ',
                'LAST_INVOICE_NUMBER' => 1
            ];
            InvoiceSetting::insert($data);

        }

        return $this->builder()->get()->first();
    }

    /**
     * Update setting
     *
     * @param  array $attributes
     * @param Setting $setting
     * @return bool
     */
    public function update(array $attributes = [])
    {
        $updated = 
            InvoiceSetting::where('SETTING_ID', $attributes["edited_setting_id"])
                ->update([
                    'PAYMENT_PERIOD' => $attributes["edited_term_of_payment"], 
                    'AUTHORIZED_NAME' => $attributes["edited_authorized_name"],
                    'AUTHORIZED_POSITION' => $attributes["edited_authorized_position"],
                    'NOTE_MESSAGE' => $attributes["edited_note_message"],
                    'INVOICE_NUMBER_PREFIX' => $attributes["edited_invoice_number_prefix"],
                    'LAST_INVOICE_NUMBER' => $attributes["edited_last_invoice_number"]
                ]);

        return $updated;
    }

}
