<?php

namespace App\Http\Controllers\User;

use Auth;
use App\Http\Controllers\Controller;
use App\Repositories\CreditRepositories;
use App\Repositories\ApiUserRepositories;
use App\Models\Credit;
use Illuminate\Http\Request;

class CreditController extends Controller
{
    /**
     * Create a credit controller instance.
     *
     * @return void
     */
    function __construct(CreditRepositories $creditRepo, ApiUserRepositories $apiUserRepo)
    {
        $this->middleware('auth');
        $this->creditRepo = $creditRepo;
        $this->apiUserRepo = $apiUserRepo;
    }

    /**
     * Show the credit page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($USER_ID)
    {
        $all_credits = $this->creditRepo->allJoinedData($USER_ID);
        $user = $this->apiUserRepo->userDetail($USER_ID);
        
        return view('user.manage_credit', ['all_credits'=>$all_credits, 'user' => $user]);
    }

    /**
     * Top up a newly created credit.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function top_up(Request $request)
    {
        $this->validate($request, $this->validationRulesForTopUp());
        
        $input = $this->processRequestInput($request);

        $saved = $this->creditRepo->top_up($input);
        
        return $saved
                ? redirect()->route('credit.detail',['USER_ID' => $input['added_user_id']])->with('alert-success', trans('validation.success_top_up', ['name' => 'Account '.$input['added_username']])) : back()->withInput()->withErrors([trans('validation.failed_top_up', ['name' => 'Account'])]);
    }

    /**
     * Deduct credit.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deduct(Request $request)
    {
        $this->validate($request, $this->validationRulesForDeduct());
        
        $input = $this->processRequestInput($request);

        $saved = $this->creditRepo->deduct($input); return $saved ? redirect()->route('credit.detail',['USER_ID' => $input['added_user_id']])->with('alert-success', trans('validation.success_deduct', ['name' => 'Account '.$input['added_username']])) : back()->withInput()->withErrors([trans('validation.failed_deduct', ['name' => 'Account'])]);
    }

    /**
     * Payment Acknowledgement.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update_payment_acknowledgement(Request $request)
    {
        $this->validate($request, $this->validationRulesForPA());
        
        $input = $this->processRequestInput($request);

        $saved = $this->creditRepo->update_payment_acknowledgement($input);
        
        return $saved
                ? redirect()->route('credit.detail',['USER_ID' => $input['added_user_id']])->with('alert-success', trans('validation.success_payment_ack', ['name' => 'Account '.$input['added_username']])) : back()->withInput()->withErrors([trans('validation.failed_payment_ack', ['name' => 'Account'])]);
    }

    /**
     * Update credit.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, $this->validationRulesForUpdate());
        
        $input = $this->processRequestInput($request);
        
        $saved = $this->creditRepo->update($input);
        
        return $saved
                ? redirect()->route('credit.detail',['USER_ID' => $input['edited_user_id']])->with('alert-success', trans('validation.success_update', ['name' => 'Transaction Ref '.$input['edited_transaction_ref']])) : back()->withInput()->withErrors([trans('validation.failed_update', ['name' => 'Transaction Ref'])]);
    }

    /**
     * Validation rules for top up.
     *
     * @return array
     */
    protected function validationRulesForTopUp()
    {
        return [
            'added_requested_by' => ['required'],
            'added_credit' => ['required','integer'],
            'added_price' => ['required','integer'],
            'added_currency' => ['required'],
            'added_payment_method' => ['required'],
        ];
    }

    /**
     * Validation rules for update credit.
     *
     * @return array
     */
    protected function validationRulesForUpdate()
    {
        return [
            'edited_requested_by' => ['required'],
            'edited_price' => ['required','integer'],
            'edited_currency' => ['required'],
            'edited_payment_method' => ['required'],
        ];
    }

    /**
     * Validation rules for deduct.
     *
     * @return array
     */
    protected function validationRulesForDeduct()
    {
        return [
            'added_credit_deduct' => ['required','integer'],
        ];
    }

    /**
     * Validation rules for payment acknowledgement.
     *
     * @return array
     */
    protected function validationRulesForPA()
    {
        return [
            'payment_date_acknowledgement' => ['required','date_format:Y-m-d'],
        ];
    }

    /**
     * Process the input value from request before store to database
     *
     * @param Request $request
     * @return array
     */
    protected function processRequestInput(Request $request){
        $input = $request->all();

        return $input;
    }
}
