<?php

namespace Tests\Unit\User;

use Tests\TestCase;
use App\Models\User;
use App\Models\Billing\BillingProfile;
use App\Models\Billing\ReportGroup;
use Session;
use Auth;

class RGroupTest extends TestCase
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
     * Test Visit Report Group
     *
     * @return  void
     */
    public function test_visit_rgroup_page()
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
         * Get Report Group Page
         */

        $response = $this->get('billing');
        $response->assertStatus(200);

        /**
         * Check the delete user is done (Positive Test Case)
         */

        $deleteUsername = User::where('ADMIN_USERNAME', 'username_21')->delete();
        $this->assertEquals(1, $deleteUsername);
    }

	/**
     * insert & delete Report Group test example.
     *
     * @return void
     */
    public function test_report_group_crud()
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
         * Get rg edit page if login passed (Positive Test Case)
         */

        $response = $this->get('billing');
        $response->assertStatus(200);

        /**
         * Create new rg with Positive Attribute Value (UI Test)
         */

        $rg = factory(ReportGroup::class)->create();

        $positiveResponseWithValidation = $this->post(route('report_group.create'), [
            "_token" => Session::token(),
            "added_billing_report_group_id" => 99992,
            "added_report_group_name" => 'jhkl',
            "added_report_group_description" => 'des',
            "added_report_group_users" => [1,3],
        ]);

        /**
         * testing the response with asserting the session has no errors. (Positive Test Case)
         */

        $positiveResponseWithValidation->assertSessionHasNoErrors();

        /**
         * Create new rg with Negative Attribute Value
         */

        $positiveResponseWithValidation = $this->post(route('report_group.create'), [
            "_token" => Session::token(),
            "added_billing_report_group_id" => 99992,
            "added_report_group_name" => 'jhkl',
            "added_report_group_description" => 'des',
            "added_report_group_users" => [1,3],
        ]);

        /**
         * testing the response with asserting the session has errors. (Negative Test Case)
         */

        $positiveResponseWithValidation->assertSessionHasErrors();

        /**
         * Update rg and get the response
         */
        
        $responseWithValidation = $this->post(route('report.group.update'), [
            "_token" => Session::token(),
            "edited_rg_id" => 99991,
            "edited_report_group_name" => 'jhkl3',
            "edited_description" => "des3",
            "edited_users" => [1,3],
        ]);

        /**
         * testing the response with asserting the session has no errors. (Negative Test Case)
         */
        $responseWithValidation->assertSessionHasNoErrors();

        /**
         * Update rg and get the response (Positive Test Case)
         */
        
        $responseWithValidation = $this->post(route('report.group.update'), [
            "_token" => Session::token(),
            "edited_rg_id" => 99991,
            "edited_description" => "des3",
            "edited_users" => [1,3],
        ]);

        $responseWithValidation->assertSessionHasErrors();
        
        /**
         * Find Report Group (Positive API Test Case)
         */

        $response = $this->get('find_users_rg/99991');
        $response->assertStatus(200);

        /**
         * Check the delete rg is done (Positive Test Case)
         */

        $response = $this->get('report_group/99991');
        $response->assertStatus(302);

        $response = $this->get('report_group/99992');
        $response->assertStatus(302);

        $deleteUsername = User::where('ADMIN_USERNAME', 'username_21')->delete();
        $this->assertEquals(1, $deleteUsername);
        
    }
}
