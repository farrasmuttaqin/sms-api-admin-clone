<?php

namespace App\Http\Controllers\Cobrander;

use Auth;
use App\Models\Cobrander;
use App\Http\Controllers\Controller;
use App\Repositories\ApiUserRepositories;
use App\Repositories\CobranderRepositories;
use App\Repositories\OperatorRepositories;
use App\Repositories\AgentRepositories;
use Illuminate\Http\Request;

class CobranderController extends Controller
{
    /**
     * Create a new CobranderController instance.
     *
     * @return void
     */
    function __construct(ApiUserRepositories $apiRepo, CobranderRepositories $cobranderRepo, OperatorRepositories $operatorRepo, AgentRepositories $agentRepo)
    {
        $this->middleware('auth');
        $this->cobranderRepo = $cobranderRepo;
        $this->operatorRepo = $operatorRepo;
        $this->apiRepo = $apiRepo;
        $this->agentRepo = $agentRepo;
    }

    /**
     * Show cobrander page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $all_cobranders = $this->cobranderRepo->allJoinedData();
        $all_operators = $this->operatorRepo->data();
        $all_agents = $this->agentRepo->data();
        $all_users = $this->apiRepo->userData();

        return view('cobrander/index_cobrander',[ 'all_users'=>$all_users, 'all_cobranders' => $all_cobranders, 'all_operators' => $all_operators, 'all_agents' => $all_agents]);
    }

    /**
     * Store a newly created cobrander.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->validationRulesForCreate());
        
        $input = $this->processRequestInput($request);
        
        $saved = $this->cobranderRepo->save($input);
        
        return $saved
                ? redirect()->route('cobrander')->with('alert-success', trans('validation.success_save', ['name' => 'Cobrander '.$input['added_cobrander_name']])) : back()->withInput()->withErrors([trans('validation.failed_save', ['name' => 'Cobrander'])]);
    }

    /**
     * Update the cobrander.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  integer $cobrander
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, $this->validationRulesForUpdate());
        
        $input = $this->processRequestInput($request);
        
        $updated = $this->cobranderRepo->update($input);

        return $updated 
                ? redirect()->route('cobrander')->with('alert-success', trans('validation.success_update', ['name' => 'Cobrander '.$input["edited_cobrander_name"]])) : back()->withInput()->withErrors([trans('validation.failed_update', ['name' => 'Cobrander'])]);
    }

    /**
     * Remove the cobrander, with COBRANDER_ID
     *
     * @param  integer $COBRANDER_ID
     * @return \Illuminate\Http\Response
     */
    public function destroy($COBRANDER_ID)
    {
        $explode_id = explode('"',$COBRANDER_ID);
        // dd($explode_id);

        if (count($explode_id) == 1){
            $explode_id = $explode_id;
            $cobranderName = Cobrander::where('COBRANDER_ID',$explode_id)->first();
        }else{
            $COBRANDER_ID = $explode_id[1];
            $explode_id = explode(',',$COBRANDER_ID);
            $cobranderName = Cobrander::where('COBRANDER_ID',$explode_id[0])->first();
        }

        $deleted = $this->cobranderRepo->delete($explode_id);

        return $deleted
                ? redirect()->route('cobrander')->with('alert-success', trans('validation.success_delete', ['name' => 'Cobrander with name '.$cobranderName["COBRANDER_NAME"]])): back()->withInput()->withErrors([trans('validation.failed_delete', ['name' => 'Cobrander'])]);
    }

    /**
     * Validation rules for store a newly created cobrander.
     *
     * @return array
     */
    protected function validationRulesForCreate()
    {
        return [
            'added_cobrander_name' => ['required', 'string','max:20'],
            'added_agent_id' => ['required'],
            'added_operator_name' => ['required'],
        ];
    }

    /**
     * Validation rules for update cobrander.
     *
     * @return array
     */
    protected function validationRulesForUpdate()
    {
        return [
            'edited_cobrander_name' => ['required', 'string','max:20'],
            'edited_agent_id' => ['required'],
            'edited_operator_name' => ['required'],
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

    /**
     * Get All Operator | For API
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getAllOperators(Request $request)
    {
        $input = $request->all();

        $search = $input["search"];
        
        if($search == ''){
            $all_operators = $this->operatorRepo->data();
        }else{
            $all_operators = $this->operatorRepo->searchData($search);
        }
        foreach($all_operators as $operator){
            $results[] = [
                'id'    => $operator->OPERATOR_NAME,
                'text'  => $operator->OPERATOR_NAME,
                'value'  => $operator->OPERATOR_NAME
            ];
        }
        
        return response()->json($results);
    }
}
