<?php

namespace App\Http\Controllers\Cobrander;

use Auth;
use App\Models\Agent;
use App\Http\Controllers\Controller;
use App\Repositories\AgentRepositories;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    /**
     * Create a new AgentController instance.
     *
     * @return void
     */
    function __construct(AgentRepositories $agentRepo)
    {
        $this->middleware('auth');
        $this->agentRepo = $agentRepo;
    }

    /**
     * Show agent page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $all_agents = $this->agentRepo->allJoinedData();

        return view('cobrander/index_agent',[ 'all_agents' => $all_agents ]);
    }

    /**
     * Store a newly created agent.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->validationRulesForCreate());
        
        $input = $this->processRequestInput($request);
        
        $saved = $this->agentRepo->save($input);
        
        return $saved
                ? redirect()->route('agent')->with('alert-success', trans('validation.success_save', ['name' => 'Agent '.$input['added_agent_name']])) : back()->withInput()->withErrors([trans('validation.failed_save', ['name' => 'Agent'])]);
    }

    /**
     * Update the agent.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  integer $agent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, $this->validationRulesForUpdate());
        
        $input = $this->processRequestInput($request);
        
        $updated = $this->agentRepo->update($input);

        return $updated 
                ? redirect()->route('agent')->with('alert-success', trans('validation.success_update', ['name' => 'Agent '.$input["edited_agent_name"]])) : back()->withInput()->withErrors([trans('validation.failed_update', ['name' => 'Agent'])]);
    }

    /**
     * Remove the agent, with AGENT_ID
     *
     * @param  integer $AGENT_ID
     * @return \Illuminate\Http\Response
     */
    public function destroy($AGENT_ID)
    {
        $agentName = Agent::where('AGENT_ID',$AGENT_ID)->first();

        $deleted = $this->agentRepo->delete($AGENT_ID);

        return $deleted
                ? redirect()->route('agent')->with('alert-success', trans('validation.success_delete', ['name' => 'Agent with name '.$agentName["AGENT_NAME"]])) : back()->withInput()->withErrors([trans('validation.failed_delete', ['name' => 'Agent'])]);
    }

    /**
     * Validation rules for store a newly created agent.
     *
     * @return array
     */
    protected function validationRulesForCreate()
    {
        return [
            'added_agent_name' => ['required', 'string','max:20', 'unique:'.env('DB_BILL_U_MESSAGE').'.AGENT,AGENT_NAME'],
            'added_agent_queue_name' => ['required', 'string','max:20'],
            'added_agent_description' => ['required', 'string'],
        ];
    }

    /**
     * Validation rules for update agent.
     *
     * @return array
     */
    protected function validationRulesForUpdate()
    {
        return [
            'edited_agent_name' => ['required', 'string','max:20', 'unique:'.env('DB_BILL_U_MESSAGE').'.AGENT,AGENT_NAME'],
            'edited_agent_queue_name' => ['required', 'string','max:20'],
            'edited_agent_description' => ['required', 'string'],
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
