<?php

namespace Tests\Unit\User;

use Tests\TestCase;
use App\Models\User;
use App\Models\ApiUser;
use App\Models\Invoice\InvoiceBank;
use Session;
use Auth;

class InvoiceBankTest extends TestCase
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
     * Test Visit Bank Page
     *
     * @return  void
     */
    public function test_visit_bank_page()
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
         * Get invoice bank page if login passed (Positive Test Case)
         */
        $response = $this->get('invoice');
        $response->assertStatus(200);

        /**
         * Check the delete user is done (Positive Test Case)
         */

        $deleteUsername = User::where('ADMIN_USERNAME', 'username_21')->delete();
        $this->assertEquals(1, $deleteUsername);
    }

	/**
     * insert & delete invoice bank unit test example.
     *
     * @return void
     */
    public function test_bank_crud()
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
         * Create new bank with Positive Attribute Value (UI Test)
         */

        $positiveResponseWithValidation = $this->post(route('bank.create'), [
            "_token" => Session::token(),
            "added_bank_id" => 9999991,
            "added_bank_name" => 'BCA-test',
            "added_address" => 'Jaksel',
            "added_account_name" => 'BCA_ACC',
            "added_account_number" => 123232,
        ]);

        /**
         * testing the response with asserting the session has no errors. (Positive Test Case)
         */

        $positiveResponseWithValidation->assertSessionHasNoErrors();

        /**
         * Create new bank with Negative Attribute Value
         */

        $positiveResponseWithValidation = $this->post(route('bank.create'), [
            "_token" => Session::token(),
            "added_bank_id" => 9999991,
            "added_bank_name" => 'BCA-test',
            "added_address" => 'Jaksel',
            "added_account_name" => 'BCA_ACC',
            "added_account_number" => 123232,
        ]);

        /**
         * testing the response with asserting the session has errors. (Positive Test Case)
         */

        $positiveResponseWithValidation->assertSessionHasErrors();

        /**
         * Update bank with Positive then Negative Attribute Value (UI Test)
         */

        $positiveResponseWithValidation = $this->post(route('bank.update'), [
            "_token" => Session::token(),
            "edited_bank_id" => 9999991,
            "edited_bank_name" => 'BCA-teste',
            "edited_address" => 'Jaksel',
            "edited_account_name" => 'BCA_ACC',
            "edited_account_number" => 1232322,
        ]);
        $positiveResponseWithValidation->assertSessionHasNoErrors();
        
        $positiveResponseWithValidation = $this->post(route('bank.update'), [
            "_token" => Session::token(),
            "edited_bank_id" => 9999991,
            "edited_bank_name" => 'BCA-teste',
            "edited_address" => 'Jaksel',
            "edited_account_name" => 'BCA_ACC',
            "edited_account_number" => 1232322,
        ]);
        $positiveResponseWithValidation->assertSessionHasErrors();

        /**
         * Update setting with Positive then Negative Attribute Value (UI Test)
         */

        $positiveResponseWithValidation = $this->post(route('setting.update'), [
            "_token" => Session::token(),
            "edited_setting_id" => 1,
            "edited_term_of_payment" => '12',
            "edited_authorized_name" => '12',
            "edited_authorized_position" => '12',
            "edited_note_message" => '12'.rand(0,100),
            "edited_invoice_number_prefix" => '1rstwap -',
            "edited_last_invoice_number" => 49,
        ]);
        $positiveResponseWithValidation->assertSessionHasNoErrors();
        

        /**
         * Check the delete bank is done (Positive Test Case)
         */

        $response = $this->get('invoice/bank/9999991');
        $response->assertStatus(302);

        $deleteUsername = User::where('ADMIN_USERNAME', 'username_21')->delete();
        $this->assertEquals(1, $deleteUsername);
    }
}
