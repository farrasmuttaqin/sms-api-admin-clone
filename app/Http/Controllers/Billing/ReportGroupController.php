<?php

namespace App\Http\Controllers\Billing;

use Auth;
use App\Models\Billing\ReportGroup;
use App\Http\Controllers\Controller;
use App\Repositories\ReportGroupRepositories;
use Illuminate\Http\Request;

class ReportGroupController extends Controller
{
    /**
     * Create a new ReportGroupController instance.
     *
     * @return void
     */
    function __construct(ReportGroupRepositories $reportGroupRepo)
    {
        $this->middleware('auth');
        $this->reportGroupRepo = $reportGroupRepo;
    }

    /**
     * Store a newly created billing report group profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->validationRulesForCreate());
        
        $input = $this->processRequestInput($request);
        
        $saved = $this->reportGroupRepo->save($input);
        
        return $saved ? 
                redirect()->route('billing')->with('alert-success', trans('validation.success_save', ['name' => 'Billing Report Group '.$input['added_report_group_name']])) 
                : back()->withInput()->withErrors([trans('validation.failed_save', ['name' => 'Billing Report Group'])]);
    }

    /**
     * Update the billing report group.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  integer $agent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $input = $this->processRequestInput($request);

        $this->validate($request, $this->validationRulesForUpdate($input['edited_rg_id']));
        
        $updated = $this->reportGroupRepo->update($input);

        return $updated ? 
                redirect()->route('billing')->with('alert-success', trans('validation.success_update', ['name' => 'Billing Report Group '.$input['edited_report_group_name']])) 
                : back()->withInput()->withErrors([trans('validation.failed_update', ['name' => 'Billing Report Group'])]);
    }

    /**
     * Remove the BILLING_REPORT_GROUP_ID, with BILLING_REPORT_GROUP_ID
     *
     * @param  integer $BILLING_REPORT_GROUP_ID
     * @return \Illuminate\Http\Response
     */
    public function destroy($BILLING_REPORT_GROUP_ID)
    {
        $billingName = ReportGroup::where('BILLING_REPORT_GROUP_ID',$BILLING_REPORT_GROUP_ID)->first();

        $deleted = $this->reportGroupRepo->delete($BILLING_REPORT_GROUP_ID);

        return $deleted ? redirect()->route('billing')->with('alert-success', trans('validation.success_delete', ['name' => 'Billing Report Group with name '.$billingName["NAME"]])) 
        : back()->withInput()->withErrors([trans('validation.failed_delete', ['name' => 'Billing Report Group'])]);
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
     * Validation rules for store a newly created billing report group.
     *
     * @return array
     */
    protected function validationRulesForCreate()
    {
        return [
            'added_report_group_name' => ['required', 'string', 'min:0', 'max:50', 'unique:'.env('DB_BILL_PRICELIST').'.BILLING_REPORT_GROUP,NAME'],
            'added_report_group_users' => ['required'],
        ];
    }

    /**
     * Validation rules for store a newly created billing report group.
     *
     * @return array
     */
    protected function validationRulesForUpdate($id)
    {
        return [
            'edited_report_group_name' => ['required', 'string', 'min:0', 'max:50', 'unique:'.env('DB_BILL_PRICELIST').'.BILLING_REPORT_GROUP,NAME,'.$id.',BILLING_REPORT_GROUP_ID'],
            'edited_users' => ['required'],
        ];
    }

    /**
     * Find Billing Report Group Users
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function findBillingUsersReportGroup($BILLING_REPORT_GROUP_ID)
    {
        $billingUsers = $this->reportGroupRepo->billingUsers($BILLING_REPORT_GROUP_ID);

        return response()->json($billingUsers, 200);
    }
}
