<?php

namespace App\Http\Controllers\User;

use Auth;
use Validator;
use App\Models\Sender;
use App\Http\Controllers\Controller;
use App\Repositories\SenderRepositories;
use Illuminate\Http\Request;

class SenderController extends Controller
{
    /**
     * Create a new Sender Controller instance.
     *
     * @return void
     */
    function __construct(SenderRepositories $senderRepo)
    {
        $this->middleware('auth');
        $this->senderRepo = $senderRepo;
    }

    /**
     * Store a newly created Sender.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $input = $this->processRequestInput($request);

        $validator = Validator::make($input,$this->validationRulesForCreate());

        if ($validator->fails()) {
            return back()->with('senderRedirect',1)->withInput()->withErrors($validator);
        }

        $saved = $this->senderRepo->save($input);
        
        return $saved
                ? redirect()->route('user.detail_edit',['USER_ID' => $input['added_user_id']])->with('senderRedirect',1)->with('alert-success', trans('validation.success_save', ['name' => 'Sender '.$input['added_sender_name']])) : back()->withInput()->withErrors([trans('validation.failed_save', ['name' => 'Sender'])]);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  integer $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $input = $this->processRequestUpdate($request);

        $sID = $input["edited_sender_id"];
        
        $validator = Validator::make($input,$this->validationRulesForEdit($sID));

        if ($validator->fails()) {
            return back()->with('senderRedirect',1)->withInput()->withErrors($validator);
        }
        
        $updated = $this->senderRepo->update($input);

        return $updated 
                ? redirect()->route('user.detail_edit',['USER_ID' => $input["edited_user_id"]])->with('senderRedirect',1)->with('alert-success', trans('validation.success_update', ['name' => 'Sender '.$input["edited_sender_name"]])) : back()->withInput()->withErrors([trans('validation.failed_update', ['name' => 'Sender'])]);
    }

    /**
     * Validation rules for store a newly created Sender.
     *
     * @return array
     */
    protected function validationRulesForCreate()
    {
        return [
            'added_sender_name' => ['required','string','max:20'],//,'unique:'.env('DB_SMS_API_ADMIN').'.SENDER,SENDER_NAME'],
            'added_user_id' => ['required'],
            'added_cobrander_id' => ['required'],
        ];
    }

    /**
     * Validation rules for edit a Sender.
     *
     * @return array
     */
    protected function validationRulesForEdit($sID)
    {
        return [
            'edited_sender_name' => ['required','string','max:20'],//'unique:'.env('DB_SMS_API_ADMIN').'.SENDER,SENDER_NAME,'.$sID.',SENDER_ID'],
            'edited_user_id' => ['required'],
            'edited_cobrander_id' => ['required'],
        ];
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

        if (!$request->get('added_sender_enabled')) {
            $input['added_sender_enabled'] = 0;
        }

        return $input;
    }

    /**
     * Process the input value from request before update to database
     *
     * @param Request $request
     * @return array
     */
    protected function processRequestUpdate(Request $request)
    {
        $update = $request->all();
        
        if (!$request->get('edited_sender_enabled')) {
            $update['edited_sender_enabled'] = 0;
        }

        return $update;
    }
}
