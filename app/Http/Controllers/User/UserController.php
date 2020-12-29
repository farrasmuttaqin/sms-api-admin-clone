<?php

namespace App\Http\Controllers\User;

use Auth;
use File;
use ZipArchive;
use App\Models\ApiUser;
use App\Http\Controllers\Controller;
use App\Repositories\ApiUserRepositories;
use App\Repositories\ClientRepositories;
use App\Repositories\CobranderRepositories;
use App\Repositories\IPRepositories;
use App\Repositories\VNRepositories;
use App\Repositories\SenderRepositories;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Create a new ClientController instance.
     *
     * @return void
     */
    function __construct(ApiUserRepositories $apiUserRepo, SenderRepositories $senderRepo, ClientRepositories $clientRepo,CobranderRepositories $cobranderRepo, IPRepositories $IPRepo, VNRepositories $VNRepo)
    {
        $this->middleware('auth');
        $this->apiUserRepo = $apiUserRepo;
        $this->clientRepo = $clientRepo;
        $this->cobranderRepo = $cobranderRepo;
        $this->IPRepo = $IPRepo;
        $this->VNRepo = $VNRepo;
        $this->senderRepo = $senderRepo;
    }

    /**
     * Show user page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $all_users = $this->apiUserRepo->allJoinedData();
        $all_users_next = $this->apiUserRepo->data();

        $all_clients = $this->clientRepo->data();
        $all_cobranders = $this->cobranderRepo->data();

        $now        = date('Y');
        $minusOne   = date("Y", strtotime("-1 year", strtotime($now)));
        $minusTwo   = date("Y", strtotime("-2 year", strtotime($now)));
        $minusThree = date("Y", strtotime("-3 year", strtotime($now)));

        $years      = [$now,$minusOne,$minusTwo,$minusThree];

        return view('user.index',[ 'years'=>$years, 'all_users' => $all_users, 'all_clients' => $all_clients, 'all_cobranders' => $all_cobranders]);
    }

    /**
     * Show user detail page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function user_detail($CLIENT_ID)
    {
        $all_users = $this->apiUserRepo->allJoinedData_detailUser($CLIENT_ID);

        $clientName = $this->apiUserRepo->companyName($CLIENT_ID);

        $all_clients = $this->clientRepo->data();
        $all_cobranders = $this->cobranderRepo->data();

        $now        = date('Y');
        $minusOne   = date("Y", strtotime("-1 year", strtotime($now)));
        $minusTwo   = date("Y", strtotime("-2 year", strtotime($now)));
        $minusThree = date("Y", strtotime("-3 year", strtotime($now)));

        $years      = [$now,$minusOne,$minusTwo,$minusThree];

        return view('user.index',[ 'years'=>$years, 'company_name'=>$clientName->COMPANY_NAME, 'from_client'=>1, 'all_users' => $all_users, 'all_clients' => $all_clients, 'all_cobranders' => $all_cobranders]);
    }
    
    /**
     * Show user detail page (only see).
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function detail($USER_ID)
    {
        $user = $this->apiUserRepo->userDetail($USER_ID);

        $all_cobranders = $this->cobranderRepo->data();

        $all_senders = $this->senderRepo->data($USER_ID);
        $all_ip = $this->IPRepo->data($USER_ID);
        $all_vn = $this->VNRepo->data($USER_ID);
        
        return view('user.detail',[ 'all_senders'=> $all_senders, 'user' => $user, 'all_vn' => $all_vn, 'all_ip' => $all_ip, 'all_cobranders' => $all_cobranders, 'only_see' => 1]);
    }

    /**
     * Show user detail page (can edit).
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function detail_edit($USER_ID)
    {
        $user = $this->apiUserRepo->userDetail($USER_ID);

        $all_cobranders = $this->cobranderRepo->data();
        $all_clients = $this->clientRepo->data();

        $all_senders = $this->senderRepo->data($USER_ID);
        $all_ip = $this->IPRepo->data($USER_ID);
        $all_vn = $this->VNRepo->data($USER_ID);
        
        return view('user.detail',[ 'all_clients' => $all_clients, 'all_senders'=> $all_senders, 'user' => $user, 'all_vn' => $all_vn, 'all_ip' => $all_ip, 'all_cobranders' => $all_cobranders, 'only_see' => 0]);
    }

    /**
     * Store a newly created user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->validationRulesForCreate());
        
        $input = $this->processRequestInput($request);

        $saved = $this->apiUserRepo->save($input);
        
        return $saved
                ? redirect()->route('user')->with('alert-success', trans('validation.success_save_user', ['name' => 'User '.$input['added_username']])): back()->withInput()->withErrors([trans('validation.failed_save', ['name' => 'User'])]);
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

        $uID = $input["edited_user_id"];
        
        $this->validate($request, $this->validationRulesForUpdate($uID));
        
        $updated = $this->apiUserRepo->update($input);

        return $updated 
                ? redirect()->route('user.detail_edit',['USER_ID' => $input["edited_user_id"]])->with('alert-success', trans('validation.success_update', ['name' => 'Account'])) : back()->withInput()->withErrors([trans('validation.failed_update', ['name' => 'Account'])]);
    }

    
    /**
     * Update user password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        $this->validate($request, $this->validationRulesForUpdatePassword());
        
        $input = $this->processRequestInput($request);

        $saved = $this->apiUserRepo->updatePassword($input);
        
        return $saved
                ? redirect()->route('user.detail_edit',['USER_ID' => $input['edited_user_id']])->with('alert-success', trans('validation.success_update_password', ['name' => ''])) : back()->withInput()->withErrors([trans('validation.failed_update_password', ['name' => ''])]);
    }

    /**
     * Activate or Deactivate User, with USER_ID
     *
     * @param  integer $USER_ID
     * @return \Illuminate\Http\Response
     */
    public function changeActiveStatus($USER_ID,$ADAPT)
    {
        $userName = ApiUser::where('USER_ID',$USER_ID)->first();

        $changeStatus = $this->apiUserRepo->changeStatus($USER_ID,$userName["ACTIVE"]);

        if (!empty($ADAPT)){
            return $changeStatus
                ? redirect()->route('user.detail_edit',['USER_ID' => $USER_ID])->with('alert-success', trans('validation.success_change_status', ['name' => 'User status with name '.$userName["USER_NAME"]])): back()->withInput()->withErrors([trans('validation.failed_change_status', ['name' => 'User'])]);
        }

        return $changeStatus
                ? redirect()->route('user')->with('alert-success', trans('validation.success_change_status', ['name' => 'User status with name '.$userName["USER_NAME"]])): back()->withInput()->withErrors([trans('validation.failed_change_status', ['name' => 'User'])]);
    }

    /**
     * Download user billing report
     *
     */
    protected function download_billing(Request $request)
    {
        $this->validate($request, $this->validationRulesForDownloadBilling());

        $input = $this->processRequestInput($request);

        $month = $input["added_report_month"];

        $year = $input["added_report_year"];

        $zip = new ZipArchive;

        $path_report = public_path('/archive/reports/'.$year.'/'.$month.'/FINAL_STATUS'); 

        $fileName = $input["added_username"].'_'.date('M', mktime(0, 0, 0, $month, 10)).'_'.$year.'.zip';

        if (File::isDirectory($path_report)){
            
            if (file_exists($path_report.'/'.$fileName)) 
                return response()->download($path_report.'/'.$fileName);

        }

        return back()->withInput()->withErrors([trans('validation.failed_download', ['name' => 'Billing Report, No Billing Report Found'])]);
        
    }

    /**
     * Download billing reports
     *
     */
    protected function download_billing_reports(Request $request)
    {
        $this->validate($request, $this->validationRulesForDownloadBillingReports());

        $input = $this->processRequestInput($request);

        $month = $input["added_billing_month"];

        if ($month < 10) $month = "0".$month;

        $year = date('Y');

        $zip = new ZipArchive;

        $path_reports = public_path('/archive/reports/'.$year.'/'.$month.'/'.'FINAL_STATUS'); 

        $fileName = 'BILLING_REPORT_'.date('M', mktime(0, 0, 0, $month, 10)).'_'.$year.'.zip';
        
        if (File::isDirectory($path_reports)){
            
            if (file_exists($path_reports.'/'.$fileName))
            {
                return response()->download($path_reports.'/'.$fileName);
            }

        }

        return back()->withInput()->withErrors([trans('validation.failed_download_billing_reports', ['name' => 'Billing Reports, No Billing Report Found'])]);

    }

    /**
     * Validation rules for download billing reports.
     *
     * @return array
     */
    protected function validationRulesForDownloadBillingReports()
    {
        return [
            'added_billing_month' => ['required'],
        ];
    }

    /**
     * Validation rules for download user billing report.
     *
     * @return array
     */
    protected function validationRulesForDownloadBilling()
    {
        return [
            'added_report_month' => ['required'],
            'added_report_year' => ['required'],
        ];
    }

    /**
     * Validation rules for store a newly created user.
     *
     * @return array
     */
    protected function validationRulesForCreate()
    {
        return [
            'added_client' => ['required'],
            'added_username' => ['required', 'regex:/^\S*$/u', 'string','max:32', 'unique:'.env('DB_SMS_API_ADMIN').'.USER,USER_NAME'],
            'added_password' => ['required', 'string','max:32'],
            'added_cobrander' => ['required'],
            'added_status_delivery' => ['required'],
            'added_is_postpaid' => ['required'],
            'added_is_bl' => ['required'],
            'added_isojk' => ['required'],
        ];
    }

    /**
     * Validation rules for store a newly created user.
     *
     * @return array
     */
    protected function validationRulesForUpdate($uID)
    {
        return [
            'edited_client_id' => ['required'],
            'edited_cobrander_id' => ['required'],
            'edited_status_delivery' => ['required'],
            'edited_is_postpaid' => ['required'],
            'edited_is_bl' => ['required'],
            'edited_isojk' => ['required'],
        ];
    }

    /**
     * Validation rules for update password.
     *
     * @return array
     */
    protected function validationRulesForUpdatePassword()
    {
        return [
            'edited_user_password' => ['required','max:32'],
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
        
        if (!$request->get('added_user_activate')) {
            $input['added_user_activate'] = 0;
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
        
        if (!$request->get('edited_user_activate')) {
            $update['edited_user_activate'] = 0;
        }

        return $update;
    }
}
