<?php

namespace App\Http\Controllers\Invoice;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Invoice\InvoiceProfile;
use App\Models\Invoice\InvoiceProduct;

use App\Repositories\ProfileRepositories;
use App\Repositories\ClientRepositories;
use App\Repositories\BankRepositories;
use App\Repositories\ProductRepositories;
use App\Repositories\SettingRepositories;

class InvoiceProfileController extends Controller
{
    /**
     * Create a new InvoiceProfileController instance.
     *
     * @return void
     */
    function __construct(SettingRepositories $settingRepo, ProductRepositories $productRepo, ProfileRepositories $profileRepo, BankRepositories $bankRepo, ClientRepositories $clientRepo)
    {
        $this->middleware('auth');
        $this->profileRepo = $profileRepo;
        $this->bankRepo = $bankRepo;
        $this->clientRepo = $clientRepo;
        $this->productRepo =$productRepo;
        $this->settingRepo = $settingRepo;
    }

    /**
     * Show invoice Profile detail
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index_profile($PROFILE_ID)
    {
        $all_clients = $this->clientRepo->data();
        $all_banks = $this->bankRepo->data();
        $all_products = $this->productRepo->getProducts($PROFILE_ID);

        $profile = $this->profileRepo->joinedProfile($PROFILE_ID);

        $users = $this->profileRepo->users($profile->CLIENT_ID);

        $groups = $this->profileRepo->groups();
        $reports = $this->profileRepo->reports();
        
        $setting = $this->settingRepo->data();

        return view('invoice.index_profile',['setting'=> $setting, 'all_products'=>$all_products, 'groups'=>$groups, 'reports'=>$reports, 'users'=>$users, 'profile' => $profile,'all_clients'=>$all_clients,'all_banks'=>$all_banks]);
    }

    /**
     * Store a newly created profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->validationRulesForCreate());
        
        $input = $this->processRequestInput($request);
        
        $saved = $this->profileRepo->save($input);
        
        return $saved ? 
                redirect()->route('invoice')->with('alert-success', trans('validation.success_save', ['name' => 'Invoice Profile'])) 
                : back()->withInput()->withErrors([trans('validation.failed_save', ['name' => 'Invoice Profile'])]);
    }

    /**
     * Store a newly created product.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_product(Request $request)
    {
        $this->validate($request, $this->validationRulesForCreateProduct());
        
        $input = $this->processRequestInput($request);
        
        $saved = $this->productRepo->save($input);
        
        return $saved ? 
                redirect()->route('invoice.index.profile',['PROFILE_ID'=>$input['added_profile_id']])->with('alert-success', trans('validation.success_save', ['name' => 'Product '.$input['added_product_name']])) : back()->withInput()->withErrors([trans('validation.failed_save', ['name' => 'Product'])]);
    }

    /**
     * Store a newly created product.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_product_history(Request $request)
    {
        $this->validate($request, $this->validationRulesForCreateProduct());
        
        $input = $this->processRequestInput($request);
        
        $saved = $this->productRepo->save($input); return $saved ? redirect()->route('invoice.index.edit', ['HISTORY_ID' => $input["added_history_id"]])->with('alert-success', trans('validation.success_save', ['name' => 'Product '.$input['added_product_name']])) : back()->withInput()->withErrors([trans('validation.failed_save', ['name' => 'Product'])]);
    }

    /**
     * Update product.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update_product(Request $request)
    {
        $this->validate($request, $this->validationRulesForUpdateProduct());
        
        $input = $this->processRequestInput($request);
        
        $updated = $this->productRepo->update($input);

        return $updated ? 
                redirect()->route('invoice.index.profile',['PROFILE_ID'=>$input['edited_profile_id']])->with('alert-success', trans('validation.success_update', ['name' => 'Product '.$input['edited_product_name']])) : back()->withInput()->withErrors([trans('validation.failed_update', ['name' => 'Product'])]);
    }

    /**
     * Update product.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update_product_history(Request $request)
    {
        $this->validate($request, $this->validationRulesForUpdateProduct());
        
        $input = $this->processRequestInput($request);
        
        $updated = $this->productRepo->update($input);

        return $updated ? 
                redirect()->route('invoice.index.edit', ['HISTORY_ID' => $input["edited_history_id"]])->with('alert-success', trans('validation.success_update', ['name' => 'Product '.$input['edited_product_name']])) : back()->withInput()->withErrors([trans('validation.failed_update', ['name' => 'Product'])]);
    }

    /**
     * Remove the product.
     *
     * @param  integer $PRODUCT_ID
     * @return \Illuminate\Http\Response
     */
    public function destroy_product($PRODUCT_ID, $PROFILE_ID)
    {
        $productName = InvoiceProduct::where('PRODUCT_ID',$PRODUCT_ID)->first();

        $deleted = $this->productRepo->delete($PRODUCT_ID);

        return $deleted ? redirect()->route('invoice.index.profile',['PROFILE_ID'=>$PROFILE_ID])->with('alert-success', trans('validation.success_delete', ['name' => 'Product '.$productName["PRODUCT_NAME"]])) 
        : back()->withInput()->withErrors([trans('validation.failed_delete', ['name' => 'Product'])]);
    }

    /**
     * Remove the product.
     *
     * @param  integer $PRODUCT_ID
     * @return \Illuminate\Http\Response
     */
    public function destroy_product_history($PRODUCT_ID, $HISTORY_ID)
    {
        $productName = InvoiceProduct::where('PRODUCT_ID',$PRODUCT_ID)->first();

        $deleted = $this->productRepo->delete($PRODUCT_ID);

        return $deleted ? redirect()->route('invoice.index.edit', ['HISTORY_ID' => $HISTORY_ID])->with('alert-success', trans('validation.success_delete', ['name' => 'Product '.$productName["PRODUCT_NAME"]])) 
        : back()->withInput()->withErrors([trans('validation.failed_delete', ['name' => 'Product'])]);
    }

    /**
     * Update the profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  integer $profile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, $this->validationRulesForUpdate());
        
        $input = $this->processRequestInput($request);

        $updated = $this->profileRepo->update($input);

        return $updated ? 
                redirect()->route('invoice.index.profile',['PROFILE_ID'=>$input['edited_profile_id']])->with('alert-success', trans('validation.success_update', ['name' => 'Invoice Profile'])) 
                : back()->withInput()->withErrors([trans('validation.failed_update', ['name' => 'Invoice Profile'])]);
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
     * Validation rules for store a newly created invoice.
     *
     * @return array
     */
    protected function validationRulesForCreate()
    {
        return [
            'added_client' => ['required'],
            'added_payment_detail' => ['required'],
            'added_auto_generate' => ['required','integer'],
        ];
    }

    /**
     * Validation rules for store a newly created product.
     *
     * @return array
     */
    protected function validationRulesForCreateProduct()
    {
        return [
            'added_product_name' => ['required'],
            'added_use_period' => ['required','integer'],
        ];
    }

    /**
     * Validation rules for update product.
     *
     * @return array
     */
    protected function validationRulesForUpdateProduct()
    {
        return [
            'edited_product_name' => ['required'],
            'edited_use_period' => ['required','integer'],
        ];
    }

    /**
     * Validation rules for update invoice
     *
     * @return array
     */
    protected function validationRulesForUpdate()
    {
        return [
            'edited_payment_detail' => ['required'],
            'edited_auto_generate' => ['required','integer'],
        ];
    }

}
