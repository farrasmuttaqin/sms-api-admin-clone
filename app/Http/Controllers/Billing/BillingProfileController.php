<?php

namespace App\Http\Controllers\Billing;

use Auth;
use App\Models\Billing\BillingProfile;
use App\Http\Controllers\Controller;
use App\Repositories\BillingRepositories;
use App\Repositories\ApiUserRepositories;
use App\Repositories\TieringGroupRepositories;
use App\Repositories\ReportGroupRepositories;
use Illuminate\Http\Request;

class BillingProfileController extends Controller
{
    /**
     * Create a new BillingController instance.
     *
     * @return void
     */
    function __construct(ReportGroupRepositories $reportGroupRepo, BillingRepositories $billingRepo, ApiUserRepositories $apiUserRepo, TieringGroupRepositories $tieringGroupRepo)
    {
        $this->middleware('auth');
        $this->billingRepo = $billingRepo;
        $this->apiUserRepo = $apiUserRepo;
        $this->tieringGroupRepo = $tieringGroupRepo;
        $this->reportGroupRepo = $reportGroupRepo;
    }

    /**
     * Show the application dashboard, start default with billing.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $all_users = $this->apiUserRepo->allJoinedData();
        $all_billings = $this->billingRepo->data();

        $all_tiering_groups = $this->tieringGroupRepo->data();
        $all_tiering_group_users  = $this->tieringGroupRepo->allUsers();

        $all_report_groups = $this->reportGroupRepo->data();

        return view('billing.index', ['all_report_groups'=> $all_report_groups, 'all_users'=>$all_users, 'all_billings'=>$all_billings, 'all_tiering_group_users'=>$all_tiering_group_users , 'all_tiering_groups'=>$all_tiering_groups]);
    }

    /**
     * Store a newly created billing profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->validationRulesForCreate());
        
        $input = $this->processRequestInput($request);
        
        $saved = $this->billingRepo->save($input); return $saved ? redirect()->route('billing')->with('alert-success', trans('validation.success_save', ['name' => 'Billing '.$input['added_billing_name']])) : back()->withInput()->withErrors([trans('validation.failed_save', ['name' => 'Billing'])]);
    }

    /**
     * Update the billing.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  integer $agent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $input = $this->processRequestInput($request);

        $this->validate($request, $this->validationRulesForUpdate($input['edited_billing_id']));

        $updated = $this->billingRepo->update($input); return $updated ? redirect()->route('billing')->with('alert-success', trans('validation.success_update', ['name' => 'Billing '.$input['edited_name']])) : back()->withInput()->withErrors([trans('validation.failed_update', ['name' => 'Billing'])]);
    }

    /**
     * Remove the billing, with BILLING_ID
     *
     * @param  integer $BILLING_ID
     * @return \Illuminate\Http\Response
     */
    public function destroy($BILLING_ID)
    {
        $billingName = BillingProfile::where('BILLING_PROFILE_ID',$BILLING_ID)->first();

        $deleted = $this->billingRepo->delete($BILLING_ID, $billingName['BILLING_TYPE']);

        return $deleted ? redirect()->route('billing')->with('alert-success', trans('validation.success_delete', ['name' => 'Billing with name '.$billingName["NAME"]])) 
        : back()->withInput()->withErrors([trans('validation.failed_delete', ['name' => 'Billing'])]);
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
     * Validation rules for store a newly created billing.
     *
     * @return array
     */
    protected function validationRulesForCreate()
    {
        return [
            'added_billing_name' => ['required', 'string', 'min:0', 'max:50', 'unique:'.env('DB_BILL_PRICELIST').'.BILLING_PROFILE,NAME'],
            'added_type' => ['required'],
            'added_billing_users' => ['required'],
        ];
    }

    /**
     * Validation rules for update billing
     *
     * @return array
     */
    protected function validationRulesForUpdate($id)
    {
        return [
            'edited_name' => ['required', 'string', 'min:0', 'max:50', 'unique:'.env('DB_BILL_PRICELIST').'.BILLING_PROFILE,NAME,'.$id.',BILLING_PROFILE_ID'],
            'edited_users' => ['required'],
        ];
    }

    /**
     * Find Billing Name
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function findBillingName($search)
    {
        $billingName = $this->billingRepo->billingName($search);
        
        if ($billingName){
            $response="false";
            $statusCode = 500;
        }else{
            $response="true";
            $statusCode = 200;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Find Billing Users
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function findBillingUsers($BILLING_ID)
    {
        $billingUsers = $this->billingRepo->billingUsers($BILLING_ID);

        return response()->json($billingUsers, 200);
    }

    /**
     * Find Tiering Operator Settings
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function findTieringOperatorSettings($BILLING_ID)
    {
        $tieringOperatorSettings = $this->billingRepo->tieringOperatorSettings($BILLING_ID);

        return response()->json($tieringOperatorSettings, 200);
    }

    /**
     * Find Tiering Settings
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function findTieringSettings($BILLING_ID)
    {
        $tieringSettings = $this->billingRepo->tieringSettings($BILLING_ID);

        return response()->json($tieringSettings, 200);
    }

    /**
     * Find Operator Settings
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function findOperatorSettings($BILLING_ID)
    {
        $operatorSettings = $this->billingRepo->operatorSettings($BILLING_ID);

        return response()->json($operatorSettings, 200);
    }

}
