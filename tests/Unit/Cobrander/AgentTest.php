<?php

namespace Tests\Unit\Cobrander;

use Tests\TestCase;
use App\Models\Agent;
use App\Models\User;
use Session;
use Auth;

class AgentTest extends TestCase
{
    
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    /**
     * Test Visit Agent Management page
     *
     * @return  void
     */
    public function test_visit_agent_management_page()
    {
        /**
         * Get login page
         */
        $responseGetLoginPage = $this->get('login');
        $responseGetLoginPage->assertStatus(200);

        /**
         * Create new fake user
         */
        $user = factory(User::class)->create([
               'ADMIN_USERNAME' => 'username_21',
               'ADMIN_PASSWORD' => hash('tiger192,3','password'),
               'LOGIN_ENABLED'  => 1,
        ]);
        
        /**
         * Fake the correct login (Positive Test Case)
         */
        $this->be($user);

        /**
         * Get agent page if login passed (Positive Test Case)
         */
        $response = $this->get('agent');
        $response->assertStatus(200);

        /**
         * Check the delete user is done (Positive Test Case)
         */

        $deleteUsername = User::where('ADMIN_USERNAME', 'username_21')->delete();
        $this->assertEquals(1, $deleteUsername);
    }

	/**
     * insert & delete agent unit test example.
     *
     * @return void
     */
    public function test_agent_crud()
    {

         /**
         * Get login page
         */
        $responseGetLoginPage = $this->get('login');
        $responseGetLoginPage->assertStatus(200);

        /**
         * Create new fake user
         */
        $user = factory(User::class)->create([
               'ADMIN_USERNAME' => 'username_21',
               'ADMIN_PASSWORD' => hash('tiger192,3','password'),
               'LOGIN_ENABLED'  => 1,
        ]);
        
        /**
         * Fake the correct login (Positive Test Case)
         */
        $this->be($user);

        /**
         * Get agent page if login passed (Positive Test Case)
         */

        $response = $this->get('agent');
        $response->assertStatus(200);
        

        /**
         * Create new agent with Positive Attribute Value (Directly Test)
         */
        $agent = factory(Agent::class)->create();

        /**
         * Create new agent with Positive Attribute Value (UI Test)
         */

        $positiveResponseWithValidation = $this->post(route('agent.create'), [
            "_token" => Session::token(),
            "added_agent_id" => 9999,
            "added_agent_name" => "g",
            "added_agent_queue_name" => "g",
            "added_agent_description" => "e",
        ]);

        /**
         * testing the response with asserting the session has no errors. (Positive Test Case)
         */

        $positiveResponseWithValidation->assertSessionHasNoErrors();

        /**
         * Create new agent with Negative Attribute Value (same ID and Name)
         */
        $positiveResponseWithValidation = $this->post(route('agent.create'), [
            "_token" => Session::token(),
            "added_agent_id" => 9999,
            "added_agent_name" => "g",
            "added_agent_queue_name" => "g",
            "added_agent_description" => "e",
        ]);

        /**
         * testing the response with asserting the session has errors. (Negative Test Case)
         */
        $positiveResponseWithValidation->assertSessionHasErrors();


        /**
         * Update agent and get the response
         */
        $responseWithValidation = $this->post(route('agent.update'), [
            "_token" => Session::token(),
            "edited_agent_id" => 9999,
            "edited_agent_name" => "j",
            "edited_agent_queue_name" => "g",
            "edited_agent_description" => "e",
        ]);

        /**
         * testing the response with asserting the session has no errors. (Positive Test Case)
         */
        $responseWithValidation->assertSessionHasNoErrors();

        /**
         * Update agent and get the response
         */
        $responseWithValidation = $this->post(route('agent.update'), [
            "_token" => Session::token(),
            "edited_agent_id" => 9999,
            "edited_agent_name" => "j",
            "edited_agent_queue_name" => "g",
            "edited_agent_description" => "e",
        ]);

        /**
         * testing the response with asserting the session has errors. (Negative Test Case)
         */
        $responseWithValidation->assertSessionHasErrors();
        
        /**
         * Check the delete agent is true (Positive Test Case with Existing AGENT_ID)
         */

        $positiveResponseWithValidation = $this->get(url('agent/99991'));
        $positiveResponseWithValidation = $this->get(url('agent/9999'));

        /**
         * testing the response with asserting the session has no errors. (Positive Test Case)
        */

        $positiveResponseWithValidation->assertSessionHasNoErrors();

        /**
         * Check the delete agent is false (Negative Test Case with Unexisting AGENT_ID)
         */
         

        $negativeResponseWithValidation = $this->get(url('agent/99991'));
        
        /**
         * testing the response with asserting the session has errors. (Negative Test Case)
        */

        $negativeResponseWithValidation->assertSessionHasErrors();

        /**
         * Check the delete user is done (Positive Test Case)
         */
        
        $deleteUsername = User::where('ADMIN_USERNAME', 'username_21')->delete();
        $this->assertEquals(1, $deleteUsername);
    }
}
