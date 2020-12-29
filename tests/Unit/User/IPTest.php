<?php

namespace Tests\Unit\User;

use Tests\TestCase;
use App\Models\User;
use App\Models\ApiUser;
use App\Models\IPRestrictions;
use Session;
use Auth;

class IPTest extends TestCase
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
    public function test_visit_ip_user_management_page()
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
    public function test_ip_crud()
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
         * Get ip edit page if login passed (Positive Test Case)
         */

        $user = factory(ApiUser::class)->create();

        $response = $this->get('user/edit/1');
        $response->assertStatus(200);

        /**
         * Create new ip with Positive Attribute Value (UI Test)
         */

        $ip = factory(IPRestrictions::class)->create();

        $positiveResponseWithValidation = $this->post(route('ip.create'), [
            "_token" => Session::token(),
            "added_ip_id" => 992,
            "added_user_id" => 99991,
            "added_ip_address" => '192.168.1.2',
        ]);

        /**
         * testing the response with asserting the session has no errors. (Positive Test Case)
         */

        $positiveResponseWithValidation->assertSessionHasNoErrors();

        /**
         * Create new sender with Negative Attribute Value
         */

        $positiveResponseWithValidation = $this->post(route('ip.create'), [
            "_token" => Session::token(),
            "added_ip_id" => 992,
            "added_user_id" => 99991,
        ]);

        /**
         * testing the response with asserting the session has no errors. (Positive Test Case)
         */

        $positiveResponseWithValidation->assertSessionHasErrors();

        /**
         * Check the delete ip is done (Positive Test Case)
         */

        $response = $this->get('ip/992');
        $response->assertStatus(302);

        $response = $this->get('ip/991');
        $response->assertStatus(302);

        /**
         * Check the delete ip is failed (Positive Test Case)
         */

        $response = $this->get('ip/992');
        $response->assertStatus(302);

        $deleteApiUser = ApiUser::where('USER_ID', 99991)->delete();
        $this->assertEquals(1, $deleteApiUser);

        $deleteUsername = User::where('ADMIN_USERNAME', 'username_21')->delete();
        $this->assertEquals(1, $deleteUsername);
    }
}
