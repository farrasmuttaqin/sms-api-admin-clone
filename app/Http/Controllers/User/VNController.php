<?php

namespace App\Http\Controllers\User;

use Auth;
use Validator;
use App\Models\VirtualNumber;
use App\Http\Controllers\Controller;
use App\Repositories\VNRepositories;
use Illuminate\Http\Request;

class VNController extends Controller
{
    /**
     * Create a new VN Controller instance.
     *
     * @return void
     */
    function __construct(VNRepositories $VNRepo)
    {
        $this->middleware('auth');
        $this->VNRepo = $VNRepo;
    }

    /**
     * Store a newly created Virtual Number.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $this->processRequestInput($request);

        $validator = Validator::make($input,$this->validationRulesForCreate());

        if ($validator->fails()) {
            return back()->with('vnRedirect',1)->withInput()->withErrors($validator);
        }
        
        $saved = $this->VNRepo->save($input);
        
        return $saved
                ? redirect()->route('user.detail_edit',['USER_ID' => $input['added_user_id']])->with('vnRedirect',1)->with('alert-success', trans('validation.success_save', ['name' => 'Virtual Number '.$input['added_destination']])): back()->withInput()->withErrors([trans('validation.failed_save', ['name' => 'Virtual Number'])]);
    }

    /**
     * Remove the virtual number, with VN_ID
     *
     * @param  integer $VN_ID
     * @return \Illuminate\Http\Response
     */
    public function destroy($VN_ID)
    {
        $vn = VirtualNumber::where('VIRTUAL_NUMBER_ID',$VN_ID)->first();

        $deleted = $this->VNRepo->delete($VN_ID);

        return $deleted
                ? redirect()->route('user.detail_edit',['USER_ID' => $vn["USER_ID"]])->with('vnRedirect',1)->with('alert-success', trans('validation.success_delete', ['name' => 'Virtual Number '.$vn["DESTINATION"]])): back()->withInput()->withErrors([trans('validation.failed_delete', ['name' => 'Virtual Number'])]);
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
        $input = $this->processRequestInputUpdate($request);

        $vID = $input["edited_virtual_id"];

        $validator = Validator::make($input,$this->validationRulesForEdit($vID));

        if ($validator->fails()) {
            return back()->with('vnRedirect',1)->withInput()->withErrors($validator);
        }
        
        $updated = $this->VNRepo->update($input);

        return $updated 
                ? redirect()->route('user.detail_edit',['USER_ID' => $input["USER_ID"]])->with('vnRedirect',1)->with('alert-success', trans('validation.success_update', ['name' => 'Virtual Number '.$input["edited_virtual_destination"]])): back()->withInput()->withErrors([trans('validation.failed_update', ['name' => 'Virtual Number'])]);
    }

    /**
     * Validation rules for store a newly created VN.
     *
     * @return array
     */
    protected function validationRulesForCreate()
    {
        return [
            'added_destination' => ['required','string','max:16','unique:'.env('DB_SMS_API_ADMIN').'.VIRTUAL_NUMBER,DESTINATION'],
            'added_use_forward_url' => ['required'],
            'added_forward_url' => ['max:255'],
        ];
    }

    /**
     * Validation rules for edit a VN.
     *
     * @return array
     */
    protected function validationRulesForEdit($vID)
    {
        return [
            'edited_virtual_destination' => ['required','string','max:16','unique:'.env('DB_SMS_API_ADMIN').'.VIRTUAL_NUMBER,DESTINATION,'.$vID.',VIRTUAL_NUMBER_ID'],
            'edited_forward' => ['required'],
            'edited_URL' => ['max:255'],
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

        if (!$request->get('added_forward_url')) {
            $input['added_forward_url'] = "-";
        }

        return $input;
    }

    /**
     * Process the input value from request before update to database
     *
     * @param Request $request
     * @return array
     */
    protected function processRequestInputUpdate(Request $request)
    {
        $input = $request->all();

        if (!$request->get('edited_URL')) {
            $input['edited_URL'] = '-';
        }

        return $input;
    }
}
