<?php

namespace Tests\Unit\User;

use Tests\TestCase;
use App\Models\User;
use App\Models\ApiUser;
use App\Models\VirtualNumber;
use Session;
use Auth;

class VNTest extends TestCase
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
    public function test_visit_vn_user_management_page()
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
     * insert & delete vn unit test example.
     *
     * @return void
     */
    public function test_vn_crud()
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
         * Get vn edit page if login passed (Positive Test Case)
         */

        $user = factory(ApiUser::class)->create();

        $response = $this->get('user/edit/1');
        $response->assertStatus(200);

        /**
         * Create new vn with Positive Attribute Value (UI Test)
         */

        $vn = factory(VirtualNumber::class)->create();

        $positiveResponseWithValidation = $this->post(route('vn.create'), [
            "_token" => Session::token(),
            "added_virtual_number_id" => 992,
            "added_user_id" => 1,
            "added_destination" => '08767676844',
            "added_use_forward_url" => 0,
        ]);

        /**
         * testing the response with asserting the session has no errors. (Positive Test Case)
         */

        $positiveResponseWithValidation->assertSessionHasNoErrors();

        /**
         * Create new vn with Negative Attribute Value
         */

        $positiveResponseWithValidation = $this->post(route('vn.create'), [
            "_token" => Session::token(),
            "added_virtual_number_id" => 992,
            "added_user_id" => 1,
            "added_destination" => '081296842422',
            "added_use_forward_url" => 1,
            "added_forward_url" => 'https://google.com'
        ]);

        /**
         * testing the response with asserting the session has errors. (Negative Test Case)
         */

        $positiveResponseWithValidation->assertSessionHasErrors();

        /**
         * Update vn and get the response
         */
        
        $responseWithValidation = $this->post(route('vn.update'), [
            "_token" => Session::token(),
            "edited_virtual_id" => 992,
            "USER_ID" => 1,
            "edited_virtual_destination" => "0878767584234",
            "edited_forward" => 0,
        ]);

        /**
         * testing the response with asserting the session has no errors. (Negative Test Case)
         */
        $responseWithValidation->assertSessionHasNoErrors();

        /**
         * Update vn and get the response (Positive Test Case)
         */
        
        $responseWithValidation = $this->post(route('vn.update'), [
            "_token" => Session::token(),
            "edited_virtual_id" => 992,
            "USER_ID" => 1,
            "edited_virtual_destination" => "0878767584234",
        ]);

        $responseWithValidation->assertSessionHasErrors();

        /**
         * Check the delete vn is done (Positive Test Case)
         */
        
        $response = $this->get('vn/992');
        $response->assertStatus(302);

        $response = $this->get('vn/991');
        $response->assertStatus(302);

        $deleteApiUser = ApiUser::where('USER_ID', 99991)->delete();
        $this->assertEquals(1, $deleteApiUser);

        $deleteUsername = User::where('ADMIN_USERNAME', 'username_21')->delete();
        $this->assertEquals(1, $deleteUsername);
        
    }
}
