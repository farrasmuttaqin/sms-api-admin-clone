<?php

namespace Tests\Unit\User;

use Tests\TestCase;
use App\Models\User;
use App\Models\Billing\BillingProfile;
use App\Models\Billing\BillingProfileOperator;
use App\Models\Billing\BillingProfileOperatorPrice;
use App\Models\Billing\BillingProfileTiering;
use App\Models\Billing\BillingProfileTieringOperator;
use Session;
use Auth;

class BProfileTest extends TestCase
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
     * Test Visit Billing Profile
     *
     * @return  void
     */
    public function test_visit_bproup_page()
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
     * insert & delete Billing Profile Operator test example.
     *
     * @return void
     */
    public function test_billing_profile_operator_crud()
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
         * Get bp edit page if login passed (Positive Test Case)
         */

        $response = $this->get('billing');
        $response->assertStatus(200);

        /**
         * Create new bp with Positive Attribute Value (UI Test) (Operator Type)
         */

        $bp = factory(BillingProfile::class)->create();

        $positiveResponseWithValidation1 = $this->post(route('billing.create'), [
            "_token" => Session::token(),
            "added_billing_profile_id" => 99992,
            "added_billing_users" => [1],
            "added_type" => 'operator',
            "added_billing_name" => 'zxc',
            "added_billing_description" => 'des',
            "operatorPR" => ['price' => 100, 'price'=> 200],
            "operatorOP" => ['operator' => 'DEFAULT', 'operator'=> 'TELKOMSEL'],
        ]);

        /**
         * testing the response with asserting the session has no errors. (Positive Test Case)
         */

        $positiveResponseWithValidation1->assertSessionHasNoErrors();

        /**
         * Create new bp with Negative Attribute Value (Operator Type)
         */

        $positiveResponseWithValidation = $this->post(route('billing.create'), [
            "_token" => Session::token(),
            "added_billing_profile_id" => 99992,
            "added_billing_users" => [1],
            "added_type" => 'operator',
            "added_billing_description" => 'des',
            "operatorPR" => ['price' => 100, 'price'=> 200],
            "operatorOP" => ['operator' => 'DEFAULT', 'operator'=> 'TELKOMSEL'],
        ]);

        /**
         * testing the response with asserting the session has errors. (Negative Test Case)
         */

        $positiveResponseWithValidation->assertSessionHasErrors();

        /**
         * Update bp and get the response
         */
        
        $responseWithValidation = $this->post(route('billing.update'), [
            "_token" => Session::token(),
            "edited_billing_id" => 99992,
            "edited_users" => [1],
            "edited_type" => 'operator',
            "edited_name" => 'vbn',
            "edited_description" => "des3",
            "editOperatorPR" => ['price' => 200, 'price'=> 200],
            "editOperatorOP" => ['operator' => 'DEFAULT', 'operator'=> 'TELKOMSEL'],
        ]);

        /**
         * testing the response with asserting the session has no errors. (Negative Test Case)
         */
        $responseWithValidation->assertSessionHasNoErrors();

        /**
         * Update bp and get the response (Positive Test Case)
         */
        
        /**
         * Update bp and get the response
         */
        
        $responseWithValidation = $this->post(route('billing.update'), [
            "_token" => Session::token(),
            "edited_billing_id" => 99992,
            "edited_users" => [1],
            "edited_type" => 'operator',
            "edited_description" => "des3",
            "editOperatorPR" => ['price' => 200, 'price'=> 200],
            "editOperatorOP" => ['operator' => 'DEFAULT', 'operator'=> 'TELKOMSEL'],
        ]);

        $responseWithValidation->assertSessionHasErrors();
        
        /**
         * Find Billing Name Test Case (Positive)
         */

        $response = $this->get('find_billing_name/abc');
        $response->assertStatus(200);

        /**
         * Find Billing Name Test Case (Negative)
         */

        $response = $this->get('find_billing_name/vbn');
        $response->assertStatus(500);
        
        /**
         * Find Billing User Test Case
         */

        $response = $this->get('find_users/99992');
        $response->assertStatus(200);

        /**
         * Find Operator Settings Test Case
         */

        $response = $this->get('find_operator_settings/99992');
        $response->assertStatus(200);

        /**
         * Check the delete bp is done (Positive Test Case)
         */

        $response = $this->get('billing/99991');
        $response->assertStatus(302);

        $response = $this->get('billing/99992');
        $response->assertStatus(302);

        $deleteUsername = User::where('ADMIN_USERNAME', 'username_21')->delete();
        $this->assertEquals(1, $deleteUsername);
        
    }

    /**
     * insert & delete Billing Profile Tiering test example.
     *
     * @return void
     */
    public function test_billing_profile_tiering_crud()
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
         * Get bp edit page if login passed (Positive Test Case)
         */

        $response = $this->get('billing');
        $response->assertStatus(200);

        /**
         * Create new bp with Positive Attribute Value (UI Test) (Tiering Type)
         */

        $positiveResponseWithValidation = $this->post(route('billing.create'), [
            "_token" => Session::token(),
            "added_billing_profile_id" => 99993,
            "added_billing_users" => [1],
            "added_type" => 'tiering',
            "added_billing_name" => 'zxc2',
            "added_billing_description" => 'des',
            "tieringFR" => ['tr' => 0, 'tr'=> 10],
            "tieringUP" => ['tr' => 3, 'tr'=> 'MAX'],
            "tieringPR" => ['price' => 10, 'price'=> 20],
        ]);

        /**
         * testing the response with asserting the session has no errors. (Positive Test Case)
         */

        $positiveResponseWithValidation->assertSessionHasNoErrors();

        /**
         * Create new bp with Negative Attribute Value (Operator Type)
         */

        $positiveResponseWithValidation = $this->post(route('billing.create'), [
            "_token" => Session::token(),
            "added_billing_profile_id" => 99993,
            "added_billing_users" => [1],
            "added_billing_description" => 'des',
            "tieringFR" => ['tr' => 0, 'tr'=> 10],
            "tieringUP" => ['tr' => 3, 'tr'=> 'MAX'],
            "tieringPR" => ['price' => 10, 'price'=> 20],
        ]);

        /**
         * testing the response with asserting the session has errors. (Negative Test Case)
         */

        $positiveResponseWithValidation->assertSessionHasErrors();

        /**
         * Update bp and get the response
         */
        
        $responseWithValidation = $this->post(route('billing.update'), [
            "_token" => Session::token(),
            "edited_billing_id" => 99992,
            "edited_users" => [1],
            "edited_type" => 'tiering',
            "edited_name" => 'vbn',
            "edited_description" => "des3",
            "editTieringFR" => ['tr' => 0, 'tr'=> 10],
            "editTieringUP" => ['tr' => 3, 'tr'=> 'MAX'],
            "editTieringPR" => ['price' => 10, 'price'=> 20],
        ]);

        /**
         * testing the response with asserting the session has no errors. (Negative Test Case)
         */
        $responseWithValidation->assertSessionHasNoErrors();

        /**
         * Update bp and get the response
         */
        
        $responseWithValidation = $this->post(route('billing.update'), [
            "_token" => Session::token(),
            "edited_billing_id" => 99992,
            "edited_users" => [1],
            "edited_description" => "des3",
            "editTieringFR" => ['tr' => 0, 'tr'=> 10],
            "editTieringUP" => ['tr' => 3, 'tr'=> 'MAX'],
            "editTieringPR" => ['price' => 10, 'price'=> 20],
        ]);

        $responseWithValidation->assertSessionHasErrors();

        /**
         * Find Tiering Settings Test Case
         */

        $response = $this->get('find_tiering_settings/99993');
        $response->assertStatus(200);

        /**
         * Check the delete bp is done (Positive Test Case)
         */

        $response = $this->get('billing/99993');
        $response->assertStatus(302);

        $deleteUsername = User::where('ADMIN_USERNAME', 'username_21')->delete();
        $this->assertEquals(1, $deleteUsername);
        
    }

    /**
     * insert & delete Billing Profile Tiering-Operator test example.
     *
     * @return void
     */
    public function test_billing_profile_tiering_operator_crud()
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
         * Get bp edit page if login passed (Positive Test Case)
         */

        $response = $this->get('billing');
        $response->assertStatus(200);

        /**
         * Create new bp with Positive Attribute Value (UI Test) (Tiering-Operator Type)
         */

        $positiveResponseWithValidation = $this->post(route('billing.create'), [
            "_token" => Session::token(),
            "added_billing_profile_id" => 99994,
            "added_billing_users" => [1],
            "added_type" => 'tiering-operator',
            "added_billing_name" => 'zxc3',
            "added_billing_description" => 'des',
            "tOperatorFR" => ['tr' => 0, 'tr'=> 10],
            "tOperatorUP" => ['tr' => 3, 'tr'=> 'MAX'],
            "tOperatorOP" => ['operator' => 'TELKOMSEL'],
            "tOperatorPR" => ['price' => 30, 'price'=> 40],
        ]);

        /**
         * testing the response with asserting the session has no errors. (Positive Test Case)
         */

        $positiveResponseWithValidation->assertSessionHasNoErrors();

        /**
         * Create new bp with Negative Attribute Value (Tiering-Operator Type)
         */

        $positiveResponseWithValidation = $this->post(route('billing.create'), [
            "_token" => Session::token(),
            "added_billing_profile_id" => 99994,
            "added_billing_users" => [1],
            "added_type" => 'tiering-operator',
            "added_billing_description" => 'des',
            "tOperatorFR" => ['tr' => 0, 'tr'=> 10],
            "tOperatorUP" => ['tr' => 3, 'tr'=> 'MAX'],
            "tOperatorOP" => ['operator' => 'TELKOMSEL'],
            "tOperatorPR" => ['price' => 30, 'price'=> 40],
        ]);

        /**
         * testing the response with asserting the session has errors. (Negative Test Case)
         */

        $positiveResponseWithValidation->assertSessionHasErrors();

        /**
         * Update bp and get the response
         */
        
        $responseWithValidation = $this->post(route('billing.update'), [
            "_token" => Session::token(),
            "edited_billing_id" => 99994,
            "edited_users" => [1],
            "edited_type" => 'tiering-operator',
            "edited_name" => 'vbn',
            "edited_description" => "des3",
            "edit_tOperatorFR" => ['tr' => 0, 'tr'=> 10],
            "edit_tOperatorUP" => ['tr' => 3, 'tr'=> 'MAX'],
            "edit_tOperatorOP" => ['operator' => 'TELKOMSEL'],
            "edit_tOperatorPR" => ['price' => 30, 'price'=> 40],
        ]);

        /**
         * testing the response with asserting the session has no errors. (Positive Test Case)
         */
        $responseWithValidation->assertSessionHasNoErrors();

        /**
         * testing the response with asserting the session has errors. (Negative Test Case)
         */
        
        $responseWithValidation = $this->post(route('billing.update'), [
            "_token" => Session::token(),
            "edited_billing_id" => 99994,
            "edited_users" => [1],
            "edited_type" => 'tiering-operator',
            "edited_description" => "des3",
            "edit_tOperatorFR" => ['tr' => 0, 'tr'=> 10],
            "edit_tOperatorUP" => ['tr' => 3, 'tr'=> 'MAX'],
            "edit_tOperatorOP" => ['operator' => 'TELKOMSEL'],
            "edit_tOperatorPR" => ['price' => 30, 'price'=> 40],
        ]);

        $responseWithValidation->assertSessionHasErrors();

        /**
         * Find Tiering Operator Settings Test Case
         */

        $response = $this->get('find_tiering_operator_settings/99994');
        $response->assertStatus(200);

        /**
         * Check the delete bp is done (Positive Test Case)
         */

        $response = $this->get('billing/99994');
        $response->assertStatus(302);

        $deleteUsername = User::where('ADMIN_USERNAME', 'username_21')->delete();
        $this->assertEquals(1, $deleteUsername);
        
    }
}
