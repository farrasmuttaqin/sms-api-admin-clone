<?php

namespace App\Http\Controllers\User;

use Auth;
use Validator;
use App\Models\IPRestrictions;
use App\Http\Controllers\Controller;
use App\Repositories\IPRepositories;
use Illuminate\Http\Request;

class IPController extends Controller
{
    /**
     * Create a new ClientController instance.
     *
     * @return void
     */
    function __construct(IPRepositories $IPRepo)
    {
        $this->middleware('auth');
        $this->IPRepo = $IPRepo;
    }

    /**
     * Store a newly created IP Address.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $this->processRequestInput($request);

        $validator = Validator::make($input,$this->validationRulesForCreate());

        if ($validator->fails()) {
            return back()->with('ipRedirect',1)->withInput()->withErrors($validator);
        }

        $saved = $this->IPRepo->save($input);
        
        return $saved
                ? redirect()->route('user.detail_edit',['USER_ID' => $input['added_user_id']])->with('ipRedirect',1)->with('alert-success', trans('validation.success_save', ['name' => 'IP Address '.$input['added_ip_address']])) : back()->withInput()->withErrors([trans('validation.failed_save', ['name' => 'IP Address'])]);
    }

    /**
     * Remove the ip address, with IP_ID
     *
     * @param  integer $IP_ID
     * @return \Illuminate\Http\Response
     */
    public function destroy($IP_ID)
    {
        $ipAddress = IPRestrictions::where('USER_IP_ID',$IP_ID)->first();

        $deleted = $this->IPRepo->delete($IP_ID);

        return $deleted
                ? redirect()->route('user.detail_edit',['USER_ID' => $ipAddress["USER_ID"]])->with('ipRedirect',1)->with('alert-success', trans('validation.success_delete', ['name' => 'IP Address '.$ipAddress["IP_ADDRESS"]])) : back()->withInput()->withErrors([trans('validation.failed_delete', ['name' => 'IP Address'])]);
    }

    /**
     * Validation rules for store a newly created IP Address.
     *
     * @return array
     */
    protected function validationRulesForCreate()
    {
        return [
            'added_ip_address' => ['required','string','max:18','ip'],
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

        return $input;
    }
}
