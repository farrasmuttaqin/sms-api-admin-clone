<?php

namespace Tests\Unit\User;

use Tests\TestCase;
use App\Models\User;
use App\Models\ApiUser;
use Session;
use Auth;

class UserTest extends TestCase
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
     * Test Visit User Management page && Detail User With Create Management Page
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
         * Get user page if login passed (Positive Test Case)
         */
        $response = $this->get('user');
        $response->assertStatus(200);

        /**
         * Get detail user page if login passed based on created user(Positive Test Case)
         */

        $user = factory(ApiUser::class)->create();

        $response = $this->get('user/detail/1');
        $response->assertStatus(200);

        /**
         * Check the delete api user is done (Positive Test Case)
         */

        $deleteApiUser = ApiUser::where('USER_ID', 99991)->delete();
        $this->assertEquals(1, $deleteApiUser);

        /**
         * Check the delete user is done (Positive Test Case)
         */

        $deleteUsername = User::where('ADMIN_USERNAME', 'username_21')->delete();
        $this->assertEquals(1, $deleteUsername);
    }

	/**
     * insert & delete user unit test example.
     *
     * @return void
     */
    public function test_user_crud()
    {
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
         * Get user edit page if login passed (Positive Test Case)
         */

        $user = factory(ApiUser::class)->create();

        $response = $this->get('user/edit/1');
        $response->assertStatus(200);

        $response = $this->get('user/client/1');
        $response->assertStatus(200);

        /**
         * Create new api user with Positive Attribute Value (UI Test)
         */

        $positiveResponseWithValidation = $this->post(route('user.create'), [
            "_token" => Session::token(),
            "added_user_id" => 99992,
            "added_client" => 1,
            "added_username" => "g",
            "added_delivery_url" => "e",
            "added_password" => 'e',
            "added_cobrander" => 5,
            "added_status_delivery" => 1,
            "added_is_postpaid" => 1,
            "added_isojk" => 1,
            "added_is_bl" => 1,
        ]);

        /**
         * testing the response with asserting the session has no errors. (Positive Test Case)
         */

        $positiveResponseWithValidation->assertSessionHasNoErrors();

        /**
         * Download billing
         */

        $positiveResponseWithValidation = $this->post(route('download.user.billing'), [
            "_token" => Session::token(),
            "added_report_year" => '2020',
            "added_report_month" => '11',
            "added_username" => 'Dya',
        ]);

        /**
         * testing the response with asserting the session has no errors. (Positive Test Case)
         */

        $positiveResponseWithValidation->assertSessionHasNoErrors();

        /**
         * Download billing
         */

        $positiveResponseWithValidation = $this->post(route('download.user.billing'), [
            "_token" => Session::token(),
            "added_report_year" => '2020',
            "added_report_month" => '10',
            "added_username" => 'dycas',
        ]);

        /**
         * testing the response with asserting the session has errors. (Negative Test Case)
         */

        $positiveResponseWithValidation->assertSessionHasErrors();

        /**
         * Create new agent with Negative Attribute Value (same ID and Name)
         */
        $positiveResponseWithValidation = $this->post(route('user.create'), [
            "_token" => Session::token(),
            "added_user_id" => 99992,
            "added_client" => 1,
            "added_username" => "g",
            "added_delivery_url" => "e",
            "added_password" => 'e',
            "added_cobrander" => 5,
            "added_user_activate" => 1,
            "added_status_delivery" => 1,
            "added_is_postpaid" => 1,
            "added_isojk" => 1,
        ]);

        /**
         * testing the response with asserting the session has errors. (Negative Test Case)
         */
        $positiveResponseWithValidation->assertSessionHasErrors();

        /**
         * Update user and get the response
         */
        $responseWithValidation = $this->post(route('user.update'), [
            "_token" => Session::token(),
            "edited_user_id" => 99992,
            "edited_client_id" => 1,
            "edited_cobrander_id" => 5,
            "edited_status_delivery" => 1,
            "edited_delivery_url" => "j",
            "edited_is_postpaid" => 1,
            "edited_isojk" => 1,
            "edited_is_bl" => 1,
        ]);

        /**
         * testing the response with asserting the session has no errors. (Positive Test Case)
         */
        $responseWithValidation->assertSessionHasNoErrors();

        /**
         * Update user and get the response
         */
        $responseWithValidation = $this->post(route('user.update'), [
            "_token" => Session::token(),
            "edited_user_id" => 99992,
            "edited_client_id" => 1,
            "edited_cobrander_id" => 5,
            "edited_status_delivery" => 1,
            "edited_delivery_url" => "j",
            "edited_is_postpaid" => 1,
            "edited_isojk" => 1,
        ]);

        /**
         * testing the response with asserting the session has errors. (Negative Test Case)
         */
        $responseWithValidation->assertSessionHasErrors();

        /**
         * Update user password and get the response
         */
        $responseWithValidation = $this->post(route('user.update.password'), [
            "_token" => Session::token(),
            "edited_user_id" => 99992,
            "edited_user_password" => 'tes',
        ]);

        /**
         * testing the response with asserting the session has no errors. (Positive Test Case)
         */
        $responseWithValidation->assertSessionHasNoErrors();

        /**
         * Update user password and get the response
         */
        $responseWithValidation = $this->post(route('user.update.password'), [
            "_token" => Session::token(),
            "edited_user_id" => 99992,
            "edited_user_password" => 'tes',
        ]);

        /**
         * testing the response with asserting the session has errors. (Negative Test Case)
         */
        $responseWithValidation->assertSessionHasErrors();

        /**
         * change user status via detail or user management
         */

        $response = $this->get('user/change/99992/1');
        $response->assertStatus(302);

        $response = $this->get('user/change/99992/0');
        $response->assertStatus(302);

        /**
         * Check the delete user is done (Positive Test Case)
         */

        $deleteApiUser = ApiUser::where('USER_ID', 99991)->delete();
        $this->assertEquals(1, $deleteApiUser);
        $deleteApiUser = ApiUser::where('USER_ID', 99992)->delete();
        $this->assertEquals(1, $deleteApiUser);

        $deleteUsername = User::where('ADMIN_USERNAME', 'username_21')->delete();
        $this->assertEquals(1, $deleteUsername);
        
    }
}
