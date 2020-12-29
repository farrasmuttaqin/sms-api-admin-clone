<?php

namespace App\Http\Controllers\Client;

use Auth;
use App\Models\Client;
use App\Http\Controllers\Controller;
use App\Repositories\ClientRepositories;
use App\Repositories\CountryRepositories;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Create a new ClientController instance.
     *
     * @return void
     */
    function __construct(ClientRepositories $clientRepo, CountryRepositories $countryRepo)
    {
        $this->middleware('auth');
        $this->clientRepo = $clientRepo;
        $this->countryRepo = $countryRepo;
    }

    /**
     * Show the application dashboard, start default with client.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $all_clients = $this->clientRepo->allJoinedData();
        $all_countries = $this->countryRepo->data();
        // dd($all_clients);
        return view('client.index',['all_clients'=> $all_clients, 'all_countries' => $all_countries]);
    }

    /**
     * Store a newly created client.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->validationRulesForCreate());
        
        $input = $this->processRequestInput($request);
        
        $saved = $this->clientRepo->save($input);
        
        return $saved ? redirect()->route('home')->with('alert-success', trans('validation.success_save', ['name' => 'Client '.$input['added_company_name']])) : back()->withInput()->withErrors([trans('validation.failed_save', ['name' => 'Client'])]);
    }

    /**
     * Remove the client, with CLIENT_ID
     *
     * @param  integer $CLIENT_ID
     * @return \Illuminate\Http\Response
     */
    public function destroy($CLIENT_ID)
    {
        $clientName = Client::where('CLIENT_ID',$CLIENT_ID)->first();

        $deleted = $this->clientRepo->delete($CLIENT_ID);

        return $deleted ? redirect()->route('home')->with('alert-success', trans('validation.success_delete', ['name' => 'Client with name '.$clientName["COMPANY_NAME"]])) : back()->withInput()->withErrors([trans('validation.failed_delete', ['name' => 'Client'])]);
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
        $input = $request->all();

        $this->validate($request, $this->validationRulesForEdit($input["clientEditID"]));
        
        $updated = $this->clientRepo->update($input);return $updated ? redirect()->route('home')->with('alert-success', trans('validation.success_update', ['name' => 'Client '.$input["edited_company_name"]])) : back()->withInput()->withErrors([trans('validation.failed_update', ['name' => 'Client'])]);
    }

    /**
     * Archived or Unarchived status
     *
     */
    public function changeArchived($CLIENT_ID)
    {
        $client = Client::where('CLIENT_ID',$CLIENT_ID)->first();

        $changeStatus = $this->clientRepo->changeArchivedStatus($CLIENT_ID,$client["ARCHIVED_DATE"]);

        return $changeStatus
                ? redirect()->route('home')->with('alert-success', trans('validation.success_change_status', ['name' => 'Client status with Company Name '.$client["COMPANY_NAME"]])): back()->withInput()->withErrors([trans('validation.failed_change_status', ['name' => 'Client'])]);
    }

    /**
     * Validation rules for store a newly created client.
     *
     * @return array
     */
    protected function validationRulesForCreate()
    {
        return [
            'added_company_name' => ['required', 'string', 'min:0', 'max:100', 'unique:CLIENT,COMPANY_NAME'],
            'added_country' => ['required'],
            'added_customer_id' => ['required'],
            'added_company_url' => ['required', 'string', 'min:0', 'max:50'],
            'added_contact_name' => ['required', 'string', 'min:0', 'max:32'],
            'added_contact_email' => ['required', 'string', 'min:0', 'max:32'],
            'added_contact_phone' => ['required', 'numeric', 'min:7'],
            'added_contact_address' => ['required', 'string'],
        ];
    }

    /**
     * Validation rules for edit a client.
     *
     * @return array
     */
    protected function validationRulesForEdit($clientID)
    {
        return [
            'edited_company_name' => ['required', 'string', 'min:0', 'max:100', 'unique:CLIENT,COMPANY_NAME,'.$clientID.',CLIENT_ID'],
            'edited_country' => ['required'],
            'edited_customer_id' => ['required'],
            'edited_company_url' => ['required', 'string', 'min:0', 'max:50'],
            'edited_contact_name' => ['required', 'string', 'min:0', 'max:32'],
            'edited_contact_email' => ['required', 'string', 'min:0', 'max:32'],
            'edited_contact_phone' => ['required', 'numeric', 'min:7'],
            'edited_contact_address' => ['required', 'string'],
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
