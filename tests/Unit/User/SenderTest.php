<?php

namespace Tests\Unit\User;

use Tests\TestCase;
use App\Models\User;
use App\Models\ApiUser;
use App\Models\Sender;
use Session;
use Auth;

class SenderTest extends TestCase
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
    public function test_visit_sender_user_management_page()
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
     * insert & delete sender unit test example.
     *
     * @return void
     */
    public function test_sender_crud()
    {
        $responseGetLoginPage = $this->get('login');
        $responseGetLoginPage->assertStatus(200);

        /**
         * Create new fake sender
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
         * Get sender edit page if login passed (Positive Test Case)
         */

        $user = factory(ApiUser::class)->create();

        $response = $this->get('user/edit/1');
        $response->assertStatus(200);

        /**
         * Create new sender with Positive Attribute Value (UI Test)
         */

        $sender = factory(Sender::class)->create();

        $positiveResponseWithValidation = $this->post(route('sender.create'), [
            "_token" => Session::token(),
            "added_user_id" => 99991,
            "added_sender_id" => 992,
            "added_sender_name" => '1',
            "added_cobrander_id" => 5,
        ]);

        /**
         * testing the response with asserting the session has no errors. (Positive Test Case)
         */

        $positiveResponseWithValidation->assertSessionHasNoErrors();

        /**
         * Create new sender with Negative Attribute Value
         */

        $positiveResponseWithValidation = $this->post(route('sender.create'), [
            "_token" => Session::token(),
            "added_user_id" => 99991,
            "added_sender_id" => 992,
            "added_sender_name" => '1',
            "added_cobrander_id" => '',
        ]);

        /**
         * testing the response with asserting the session has no errors. (Positive Test Case)
         */

        $positiveResponseWithValidation->assertSessionHasErrors();

        /**
         * Update sender and get the response
         */
        $responseWithValidation = $this->post(route('sender.update'), [
            "_token" => Session::token(),
            "edited_sender_id" => 991,
            "edited_user_id" => 99991,
            "edited_sender_name" => 'tes',
            "edited_cobrander_id" => 5,
        ]);

        /**
         * testing the response with asserting the session has no errors. (Positive Test Case)
         */
        $responseWithValidation->assertSessionHasNoErrors();

        /**
         * Update user and get the response
         */
        $responseWithValidation = $this->post(route('sender.update'), [
            "_token" => Session::token(),
            "edited_sender_id" => 991,
            "edited_sender_name" => 'tes',
            "edited_sender_enabled" => 0,
            "edited_cobrander_id" => 5,
        ]);

        /**
         * testing the response with asserting the session has errors. (Negative Test Case)
         */
        $responseWithValidation->assertSessionHasErrors();

        /**
         * Check the delete sender is done (Positive Test Case)
         */

        $deleteSender = Sender::where('SENDER_ID', 991)->delete();
        $this->assertEquals(1, $deleteSender);
        $deleteSender = Sender::where('SENDER_ID', 992)->delete();
        $this->assertEquals(1, $deleteSender);

        $deleteUser = ApiUser::where('USER_ID', 99991)->delete();
        $this->assertEquals(1, $deleteUser);

        $deleteUsername = User::where('ADMIN_USERNAME', 'username_21')->delete();
        $this->assertEquals(1, $deleteUsername);
        
    }
}
