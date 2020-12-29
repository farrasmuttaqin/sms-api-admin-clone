<?php

namespace App\Http\Controllers\Billing;

use Auth;
use App\Models\Billing\TieringGroup;
use App\Http\Controllers\Controller;
use App\Repositories\TieringGroupRepositories;
use Illuminate\Http\Request;

class TieringGroupController extends Controller
{
    /**
     * Create a new TieringGroupController instance.
     *
     * @return void
     */
    function __construct(TieringGroupRepositories $tieringGroupRepo)
    {
        $this->middleware('auth');
        $this->tieringGroupRepo = $tieringGroupRepo;
    }

    /**
     * Store a newly created billing tiering group profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->validationRulesForCreate());
        
        $input = $this->processRequestInput($request);
        
        $saved = $this->tieringGroupRepo->save($input);
        
        return $saved ? 
                redirect()->route('billing')->with('alert-success', trans('validation.success_save', ['name' => 'Billing Tiering Group '.$input['added_tiering_group_name']])) 
                : back()->withInput()->withErrors([trans('validation.failed_save', ['name' => 'Billing Tiering Group'])]);
    }

    /**
     * Update the billing tiering group.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  integer $agent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $input = $this->processRequestInput($request);

        $this->validate($request, $this->validationRulesForUpdate($input['edited_tg_id']));
        
        $updated = $this->tieringGroupRepo->update($input);

        return $updated ? 
                redirect()->route('billing')->with('alert-success', trans('validation.success_update', ['name' => 'Billing Tiering Group '.$input['edited_tiering_group_name']])) 
                : back()->withInput()->withErrors([trans('validation.failed_update', ['name' => 'Billing Tiering Group'])]);
    }

    /**
     * Remove the BILLING_TIERING_GROUP_ID, with BILLING_TIERING_GROUP_ID
     *
     * @param  integer $BILLING_TIERING_GROUP_ID
     * @return \Illuminate\Http\Response
     */
    public function destroy($BILLING_TIERING_GROUP_ID)
    {
        $billingName = TieringGroup::where('BILLING_TIERING_GROUP_ID',$BILLING_TIERING_GROUP_ID)->first();

        $deleted = $this->tieringGroupRepo->delete($BILLING_TIERING_GROUP_ID);

        return $deleted ? redirect()->route('billing')->with('alert-success', trans('validation.success_delete', ['name' => 'Billing Tiering Group with name '.$billingName["NAME"]])) 
        : back()->withInput()->withErrors([trans('validation.failed_delete', ['name' => 'Billing Tiering Group'])]);
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
     * Validation rules for store a newly created billing tiering group.
     *
     * @return array
     */
    protected function validationRulesForCreate()
    {
        return [
            'added_tiering_group_name' => ['required', 'string', 'min:0', 'max:50', 'unique:'.env('DB_BILL_PRICELIST').'.BILLING_TIERING_GROUP,NAME'],
            'added_tiering_group_users' => ['required'],
        ];
    }

    /**
     * Validation rules for store a newly created billing tiering group.
     *
     * @return array
     */
    protected function validationRulesForUpdate($id)
    {
        return [
            'edited_tiering_group_name' => ['required', 'string', 'min:0', 'max:50', 'unique:'.env('DB_BILL_PRICELIST').'.BILLING_TIERING_GROUP,NAME,'.$id.',BILLING_TIERING_GROUP_ID'],
            'edited_users' => ['required'],
        ];
    }

    /**
     * Find Billing Tiering Group Users
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function findBillingUsersTieringGroup($BILLING_TIERING_GROUP_ID)
    {
        $billingUsers = $this->tieringGroupRepo->billingUsers($BILLING_TIERING_GROUP_ID);

        return response()->json($billingUsers, 200);
    }
}
