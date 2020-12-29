<?php

namespace Tests\Unit\Cobrander;

use Tests\TestCase;
use App\Models\User;
use App\Models\Cobrander;
use Session;
use Auth;

trait AbstractTrait
{
    public function concreteMethod()
    {
        return $this->abstractMethod();
    }

    public abstract function abstractMethod();
}

abstract class AbstractClass
{
    public function concreteMethod()
    {
        return $this->abstractMethod();
    }

    public abstract function abstractMethod();
}

class CobranderTest extends TestCase
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

    public function testConcreteMethod()
    {
        $mock = $this->getMockForTrait(AbstractTrait::class);

        $mock->expects($this->any())
             ->method('abstractMethod')
             ->will($this->returnValue(true));

        $this->assertTrue($mock->concreteMethod());

        $stub = $this->getMockForAbstractClass(AbstractClass::class);

        $stub->expects($this->any())
             ->method('abstractMethod')
             ->will($this->returnValue(true));

        $this->assertTrue($stub->concreteMethod());
    }

    /**
     * Test Visit Cobrander Management page
     *
     * @return  void
     */
    public function test_visit_cobrander_management_page()
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
         * Get cobrander page if login passed (Positive Test Case)
         */
        $response = $this->get('cobrander');
        $response->assertStatus(200);

        /**
         * Check the delete user is done (Positive Test Case)
         */

        $deleteUsername = User::where('ADMIN_USERNAME', 'username_21')->delete();
        $this->assertEquals(1, $deleteUsername);
    }

	/**
     * insert & delete cobrander unit test example.
     *
     * @return void
     */
    public function test_cobrander_crud()
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
         * Get cobrander page if login passed (Positive Test Case)
         */

        $response = $this->get('cobrander');
        $response->assertStatus(200);
        

        /**
         * Create new cobrander with Positive Attribute Value (Directly Test)
         */
        $cobrander = factory(Cobrander::class)->create();
        
        /**
         * Create new cobrander with Positive Attribute Value (UI Test)
         */
        $id = 100001;

        $positiveResponseWithValidation = $this->post(route('cobrander.create'), [
            "_token" => Session::token(),
            "added_cobrander_id" => $id,
            "added_cobrander_name" => "g14",
            "added_agent_id" => 5,
            "added_operator_name" => ['e6'],
        ]);

        $positiveResponseWithValidation = $this->post(route('cobrander.create'), [
            "_token" => Session::token(),
            "added_cobrander_id" => 99999991,
            "added_cobrander_name" => "g14",
            "added_agent_id" => 5,
            "added_operator_name" => ['e6'],
        ]);

        $positiveResponseWithValidation = $this->post(route('cobrander.create'), [
            "_token" => Session::token(),
            "added_cobrander_id" => 99999992,
            "added_cobrander_name" => "g14",
            "added_agent_id" => 5,
            "added_operator_name" => ['e6'],
        ]);

        /**
         * testing the response with asserting the session has no errors. (Positive Test Case)
         */

        $positiveResponseWithValidation->assertSessionHasNoErrors();

        /**
         * Create new cobrander with Negative Attribute Value (same ID and Name)
         */
        $positiveResponseWithValidation = $this->post(route('cobrander.create'), [
            "_token" => Session::token(),
            "added_cobrander_id" => $id,
            "added_cobrander_name" => "g1",
            "added_operator_name" => ["e6"],
        ]);

        /**
         * testing the response with asserting the session has errors. (Negative Test Case)
         */
        $positiveResponseWithValidation->assertSessionHasErrors();


        /**
         * Update cobrander and get the response
         */
        $responseWithValidation = $this->post(route('cobrander.update'), [
            "_token" => Session::token(),
            "edited_cobrander_id" => $id,
            "edited_cobrander_name" => "j",
            "edited_agent_id" => 2,
            "edited_operator_name" => ["e"],
        ]);

        /**
         * testing the response with asserting the session has no errors. (Positive Test Case)
         */
        $responseWithValidation->assertSessionHasNoErrors();

        /**
         * Update cobrander and get the response
         */
        $responseWithValidation = $this->post(route('cobrander.update'), [
            "_token" => Session::token(),
            "edited_cobrander_id" => $id,
            "edited_cobrander_name" => "j",
            "edited_operator_name" => "e",
        ]);

        /**
         * testing the response with asserting the session has errors. (Negative Test Case)
         */
        $responseWithValidation->assertSessionHasErrors();
        
        /**
         * Check the delete cobrander is true (Positive Test Case with Existing COBRANDER_ID)
         */

        $positiveResponseWithValidation = $this->get(url('cobrander/99991'));
        $negativeResponseWithValidation = $this->get(url('cobrander/"99999991,99999992"'));

        /**
         * testing the response with asserting the session has no errors. (Positive Test Case)
        */

        $positiveResponseWithValidation->assertSessionHasNoErrors();

        /**
         * Check the delete cobrander is false (Negative Test Case with Unexisting COBRANDER_ID)
         */
         
        $negativeResponseWithValidation = $this->get(url('cobrander/99991'));
        
        /**
         * testing the response with asserting the session has errors. (Negative Test Case)
        */

        $negativeResponseWithValidation->assertSessionHasErrors();

        /**
         * Check the delete user is done (Positive Test Case)
         */


        /**
         * Select Operator (Positive Test)
         */

        $positiveResponseWithValidation = $this->post(route('operator.all'), [
            "_token" => Session::token(),
            "search" => "",
        ]);

        /**
         * testing the response with asserting the session has no errors. (Positive Test Case)
         */

        $positiveResponseWithValidation->assertSessionHasNoErrors();

        /**
         * Select Operator (Positive Test )
         */
        $positiveResponseWithValidation = $this->post(route('operator.all'), [
            "_token" => Session::token(),
            "search" => 'telkomsel',
        ]);

        /**
         * testing the response with asserting the session has errors. (Positive Test Case)
         */
        $positiveResponseWithValidation->assertSessionHasNoErrors();
        
        $deleteUsername = User::where('ADMIN_USERNAME', 'username_21')->delete();
        $this->assertEquals(1, $deleteUsername);
    }
}
