<?php

namespace Tests\Unit\User;

use Tests\TestCase;
use App\Models\User;
use App\Models\ApiUser;
use App\Models\Credit;
use Session;
use Auth;

class CreditTest extends TestCase
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
     * Test Visit User Credit Management page && Detail User Credit With Create Management Page
     *
     * @return  void
     */
    public function test_visit_credit_user_management_page()
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
         * Get credit user page if login passed based on created user(Positive Test Case)
         */

        $user = factory(ApiUser::class)->create();

        $response = $this->get('credit/1');
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
     * positive all crud in credit unit test example.
     *
     * @return void
     */
    public function test_crud()
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
        $credit = factory(Credit::class)->create();
        

        $response = $this->get('credit/1');
        $response->assertStatus(200);

        /**
         * Top up new credit with Positive Attribute Value (UI Test)
         */

        $positiveResponseWithValidation = $this->post(route('credit.top_up'), [
            "_token" => Session::token(),
            "added_credit_transaction_id" => 991,
            "added_user_id" => 99991,
            "added_username" => 'user_username_1',
            "added_requested_by" => 'farras',
            "added_credit" => 100,
            "added_user_credit" => 200,
            "added_price" => 300,
            "added_currency" => "rp",
            "added_payment_method" => "bank",
            "added_information" => "tes",
        ]); 

        /**
         * testing the response with asserting the session has no errors. (Positive Test Case)
         */

        $positiveResponseWithValidation->assertSessionHasNoErrors();

        /**
         * Top up new credit with Negative Attribute Value (UI Test)
         */

        $positiveResponseWithValidation = $this->post(route('credit.top_up'), [
            "_token" => Session::token(),
            "added_credit_transaction_id" => 991,
            "added_user_id" => 99991,
            "added_username" => 'user_username_1',
            "added_requested_by" => 'farras',
            "added_credit" => 100,
            "added_user_credit" => 200,
            "added_price" => 300,
            "added_currency" => "rpqwewqe",
            "added_payment_method" => "bank",
            "added_information" => "tes",
        ]); 

        /**
         * testing the response with asserting the session has errors. (Negative Test Case)
         */

        $positiveResponseWithValidation->assertSessionHasErrors();

        /**
         * Top up new credit with Positive Attribute Value (UI Test)
         */

        $positiveResponseWithValidation = $this->post(route('credit.deduct'), [
            "_token" => Session::token(),
            "added_credit_transaction_id" => 991,
            "added_username" => 'user_username_1',
            "added_user_id" => 99991,
            "added_user_credit" => 100,
            "added_credit_deduct" => 200,
            "added_information_deduct" => 'tes',
        ]); 

        /**
         * testing the response with asserting the session has no errors. (Positive Test Case)
         */

        $positiveResponseWithValidation->assertSessionHasNoErrors();

        /**
         * Top up new credit with Negative Attribute Value (UI Test)
         */

        $positiveResponseWithValidation = $this->post(route('credit.deduct'), [
            "_token" => Session::token(),
            "added_credit_transaction_id" => 991,
            "added_username" => 'user_username_1',
            "added_user_id" => 99991,
            "added_user_credit" => 100,
            "added_credit_deduct" => "tes",
            "added_information_deduct" => 'tes',
        ]); 

        /**
         * testing the response with asserting the session has errors. (Negative Test Case)
         */

        $positiveResponseWithValidation->assertSessionHasErrors();

        /**
         * update_payment_acknowledgement with Positive Attribute Value (UI Test)
         */

        $positiveResponseWithValidation = $this->post(route('credit.payment_acknowledgement'), [
            "_token" => Session::token(),
            "added_transaction_id" => 991,
            "payment_date_acknowledgement" => "2020-02-02",
        ]); 

        /**
         * testing the response with asserting the session has no errors. (Positive Test Case)
         */

        $positiveResponseWithValidation->assertSessionHasNoErrors();

        /**
         * Tupdate_payment_acknowledgement with Negative Attribute Value (UI Test)
         */

        $positiveResponseWithValidation = $this->post(route('credit.payment_acknowledgement'), [
            "_token" => Session::token(),
            "added_transaction_id" => 991,
            "payment_date_acknowledgement" => "tes",
        ]); 


        /**
         * testing the response with asserting the session has errors. (Negative Test Case)
         */

        $positiveResponseWithValidation->assertSessionHasErrors();

        /**
         * update_credit with Positive Attribute Value (UI Test)
         */

        $positiveResponseWithValidation = $this->post(route('credit.update'), [
            "_token" => Session::token(),
            "edited_credit_transaction_id" => 991,
            "edited_requested_by" => "farras",
            "edited_price" =>  200,
            "edited_currency" => "rp",
            "edited_payment_method" => "bank",
            "edited_information" => "tes",
        ]); 

        /**
         * testing the response with asserting the session has no errors. (Positive Test Case)
         */

        $positiveResponseWithValidation->assertSessionHasNoErrors();

        /**
         * update_credit with Negative Attribute Value (UI Test)
         */

        $positiveResponseWithValidation = $this->post(route('credit.update'), [
            "_token" => Session::token(),
            "edited_credit_transaction_id" => 991,
            "edited_requested_by" => "farras",
            "edited_price" =>  200,
            "edited_currency" => "rpasdasdsd",
            "edited_payment_method" => "bank",
            "edited_information" => "tes",
        ]); 
        

        /**
         * testing the response with asserting the session has errors. (Negative Test Case)
         */

        $positiveResponseWithValidation->assertSessionHasErrors();

        $deleteCredit = Credit::where('CREDIT_TRANSACTION_ID', 991)->delete();
        $this->assertEquals(1, $deleteCredit);

        $deleteApiUser = ApiUser::where('USER_ID', 99991)->delete();
        $this->assertEquals(1, $deleteApiUser);

        $deleteUsername = User::where('ADMIN_USERNAME', 'username_21')->delete();
        $this->assertEquals(1, $deleteUsername);
    }
}
