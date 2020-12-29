<?php

namespace App\Http\Controllers\Invoice;

use Auth;
use App\Models\Invoice\InvoiceBank;
use App\Http\Controllers\Controller;
use App\Repositories\BankRepositories;
use App\Repositories\SettingRepositories;
use Illuminate\Http\Request;

class InvoiceBankController extends Controller
{
    /**
     * Create a new InvoiceBankController instance.
     *
     * @return void
     */
    function __construct(BankRepositories $bankRepo, SettingRepositories $settingRepo)
    {
        $this->middleware('auth');
        $this->bankRepo = $bankRepo;
        $this->settingRepo = $settingRepo;
    }

    /**
     * Store a newly created Bank.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->validationRulesForCreate());
        
        $input = $this->processRequestInput($request);
        
        $saved = $this->bankRepo->save($input);
        
        return $saved ? 
                redirect()->route('invoice')->with('alert-success', trans('validation.success_save', ['name' => 'Bank '.$input['added_bank_name']])) 
                : back()->withInput()->withErrors([trans('validation.failed_save', ['name' => 'Bank'])]);
    }

    /**
     * Update the bank.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  integer $bank
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $input = $this->processRequestInput($request);
        
        $this->validate($request, $this->validationRulesForUpdate($input['edited_bank_id']));
        
        $updated = $this->bankRepo->update($input);

        return $updated ? 
                redirect()->route('invoice')->with('alert-success', trans('validation.success_update', ['name' => 'Bank '.$input['edited_bank_name']])) 
                : back()->withInput()->withErrors([trans('validation.failed_update', ['name' => 'Bank'])]);
    }

    /**
     * Update the setting.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  integer $setting
     * @return \Illuminate\Http\Response
     */
    public function update_setting(Request $request)
    {
        $input = $this->processRequestInput($request);
        
        $this->validate($request, $this->validationRulesForUpdateSetting());
        
        $updated = $this->settingRepo->update($input);

        return $updated ? 
                redirect()->route('invoice')->with('alert-success', trans('validation.success_update', ['name' => 'Setting'])) 
                : back()->withInput()->withErrors([trans('validation.failed_update', ['name' => 'Setting'])]);
    }


    /**
     * Remove the bank.
     *
     * @param  integer $BILLING_REPORT_GROUP_ID
     * @return \Illuminate\Http\Response
     */
    public function destroy($BANK_ID)
    {
        $bankName = InvoiceBank::where('INVOICE_BANK_ID',$BANK_ID)->first();

        $deleted = $this->bankRepo->delete($BANK_ID);

        return $deleted ? redirect()->route('invoice')->with('alert-success', trans('validation.success_delete', ['name' => 'Bank '.$bankName["BANK_NAME"]])) 
        : back()->withInput()->withErrors([trans('validation.failed_delete', ['name' => 'Bank'])]);
    }

    /**
     * Process the input value from request before store to database
     *
     * @param Request $request
     * @return array
     */
    protected function processRequestInput(Request $request)
    {
        $input = $request->all();
        
        return $input;
    }

    /**
     * Validation rules for store a newly created bank.
     *
     * @return array
     */
    protected function validationRulesForCreate()
    {
        return [
            'added_bank_name' => ['required', 'unique:'.env('DB_SMS_API_V2').'.INVOICE_BANK,BANK_NAME'],
            'added_account_name' => ['required'],
            'added_account_number' => ['required','integer', 'unique:'.env('DB_SMS_API_V2').'.INVOICE_BANK,ACCOUNT_NUMBER'],
        ];
    }

    /**
     * Validation rules for update a bank.
     *
     * @return array
     */
    protected function validationRulesForUpdate($id)
    {
        return [
            'edited_bank_name' => ['required', 'unique:'.env('DB_SMS_API_V2').'.INVOICE_BANK,BANK_NAME,'.$id.',INVOICE_BANK_ID'],
            'edited_account_name' => ['required'],
            'edited_account_number' => ['required','integer', 'unique:'.env('DB_SMS_API_V2').'.INVOICE_BANK,ACCOUNT_NUMBER,'.$id.',INVOICE_BANK_ID'],
        ];
    }

    /**
     * Validation rules for update a setting.
     *
     * @return array
     */
    protected function validationRulesForUpdateSetting()
    {
        return [
            'edited_term_of_payment' => ['required','integer'],
            'edited_last_invoice_number' => ['required','integer'],
            'edited_invoice_number_prefix' => ['required','string'],
            'edited_authorized_name' => ['required','string'],
            'edited_authorized_position' => ['required','string'],
            'edited_note_message' => ['required','string'],
        ];
    }
}
