<?php

namespace Tests\Unit\Client;

use Tests\TestCase;
use App\Models\Client;
use App\Models\User;
use App\Models\Country;
use Session;
use Auth;

class ClientTest extends TestCase
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
     * Test Visit Client Management page
     *
     * @return  void
     */
    public function test_visit_user_management_page()
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
         * Get home page if login passed (Positive Test Case)
         */
        $response = $this->get('/');
        $response->assertStatus(200);

        /**
         * Check the delete user is done (Positive Test Case)
         */

        $deleteUsername = User::where('ADMIN_USERNAME', 'username_21')->delete();
        $this->assertEquals(1, $deleteUsername);
    }

    /**
     * insert & delete client unit test example.
     *
     * @return void
     */
    public function test_insert_delete_client()
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
         * Get home page if login passed (Positive Test Case)
         */
        $response = $this->get('/');
        $response->assertStatus(200);
        
        $client = factory(Client::class)->create();

        /**
         * Create new client and get the response
         */
        $responseWithValidation = $this->post(route('client.create'), [
            "_token" => Session::token(),
            "added_client_id" => 9999,
            "added_company_name" => "g",
            "added_company_name" => "g",
            "added_company_url" => "e",
            "added_country" => "IDN",
            "added_contact_name" => "e",
            "added_contact_email" => "e@e",
            "added_contact_phone" => "6281275642512",
            "added_contact_address" => "e",
            "added_customer_id" => "e",
        ]);

        /**
         * testing the response with asserting the session has no errors. (Positive Test Case)
         */
        $responseWithValidation->assertSessionHasNoErrors();
        $responseWithValidation->assertRedirect(url('/'));

        /**
         * Create new client and get the response
         */
        $responseWithValidation = $this->post(route('client.create'), [
            "_token" => Session::token(),
            "added_client_id" => 9999,
            "added_company_name" => "g",
            "added_company_name" => "g",
            "added_company_url" => "e",
            "added_country" => "IDN",
            "added_contact_name" => "e",
            "added_contact_email" => "e@e",
            "added_contact_phone" => "6281275642512",
            "added_contact_address" => "e",
            "added_customer_id" => "e",
        ]);

        /**
         * testing the response with asserting the session has no errors. (Negative Test Case)
         */
        $responseWithValidation->assertSessionHasErrors();
        $responseWithValidation->assertRedirect(url('/'));

        /**
         * testing the validation response is success and go to home page (200) (Positive Test Case).
         */
        $response = $this->get('/');
        $response->assertStatus(200);

        /**
         * Archived client is true (Positive Test Case)
         */ 

        $responseWithValidation = $this->get(url('client/change_status/9999'));
        
        /**
         * testing the response with asserting the session has errors. (Positive Test Case)
        */

        $responseWithValidation->assertSessionHasNoErrors();

        /**
         * Archived client is false (Unarchived) (Negative Test Case)
         */

        $responseWithValidation = $this->get(url('client/change_status/99994'));

        /**
         * Check the delete client is true (Positive Test Case)
         */

        $responseWithValidation = $this->get(url('client/99991'));

        /**
         * Check the delete client is true (Positive Test Case)
         */ 

        $responseWithValidation = $this->get(url('client/9999'));
        
        /**
         * testing the response with asserting the session has errors. (Positive Test Case)
        */

        $responseWithValidation->assertSessionHasNoErrors();

        /**
         * Check the delete client is false (Negative Test Case)
         */

        $responseWithValidation = $this->get(url('client/9999'));
        
        /**
         * testing the response with asserting the session has errors. (Negative Test Case)
        */

        $responseWithValidation->assertSessionHasErrors();

        /**
         * Check the delete user is done (Positive Test Case)
         */
        
        $deleteUsername = User::where('ADMIN_USERNAME', 'username_21')->delete();
        $this->assertEquals(1, $deleteUsername);

        
    }

    /**
     * update client unit test example.
     *
     * @return void
     */
    public function test_update_client()
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
         * Get home page if login passed (Positive Test Case)
         */
        $response = $this->get('/');
        $response->assertStatus(200);

        /**
         * Create new client and get the response
         */
        $client = factory(Client::class)->create();

        $responseWithValidation = $this->post(route('client.create'), [
            "_token" => Session::token(),
            "added_client_id" => 9999,
            "added_company_name" => "g",
            "added_company_name" => "g",
            "added_company_url" => "e",
            "added_country" => "IDN",
            "added_contact_name" => "e",
            "added_contact_email" => "e@e",
            "added_contact_phone" => "6281275642512",
            "added_contact_address" => "e",
            "added_customer_id" => "e",
        ]);

        /**
         * testing the response with asserting the session has no errors. (Positive Test Case)
         */
        $responseWithValidation->assertSessionHasNoErrors();

        /**
         * Update new client and get the response
         */
        $responseWithValidation = $this->post(route('client.update'), [
            "_token" => Session::token(),
            "clientEditID" => 9999,
            "edited_company_name" => "h",
            "edited_company_url" => "g",
            "edited_country" => "g",
            "edited_contact_name" => "IDN",
            "edited_contact_email" => "e@e",
            "edited_contact_phone" => "6281275642512",
            "edited_contact_address" => "e",
            "edited_customer_id" => "e",
        ]);

        /**
         * testing the response with asserting the session has no errors. (Positive Test Case)
         */
        $responseWithValidation->assertSessionHasNoErrors();

        /**
         * Update new client and get the response
         */
        $responseWithValidation = $this->post(route('client.update'), [
            "_token" => Session::token(),
            "clientEditID" => 9999,
            "edited_company_name" => "g1",
            "edited_company_url" => "g",
            "edited_country" => "g",
            "edited_contact_name" => "IDN",
            "edited_contact_email" => "e@e",
            "edited_contact_phone" => "6281275642512",
            "edited_contact_address" => "e",
            "edited_customer_id" => "e",
        ]);

        /**
         * testing the response with asserting the session has no errors. (Negative Test Case)
         */
        $responseWithValidation->assertSessionHasErrors();

        /**
         * testing the validation response is success and go to home page (200) (Positive Test Case).
         */
        $response = $this->get('/');
        $response->assertStatus(200);

        /**
         * Check the delete client is done (Positive Test Case)
         */

        $responseWithValidation = $this->get(url('client/9999'));
        $responseWithValidation = $this->get(url('client/99991'));
        
        /**
         * testing the response with asserting the session has no errors. (Positive Test Case)
        */

        $responseWithValidation->assertSessionHasNoErrors();
        
        /**
         * Check the delete user is done (Positive Test Case)
         */
        
        $deleteUsername = User::where('ADMIN_USERNAME', 'username_21')->delete();
        $this->assertEquals(1, $deleteUsername);

        
    }
}
