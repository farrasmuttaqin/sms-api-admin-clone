<?php

namespace Tests\Unit\User;

use Tests\TestCase;
use App\Models\User;
use App\Models\ApiUser;
use App\Models\Invoice\InvoiceBank;
use App\Models\Invoice\InvoiceProfile;
use App\Models\Invoice\InvoiceProduct;
use Session;
use Auth;

class InvoiceHistoryTest extends TestCase
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
     * Test Visit History Page
     *
     * @return  void
     */
    public function test_visit_history_page()
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
         * Get invoice history page if login passed (Positive Test Case)
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
     * insert & delete invoice history unit test example.
     *
     * @return void
     */
    public function test_history_crud()
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
         * Create new history with Positive Attribute Value (UI Test)
         */

        $positiveResponseWithValidation = $this->post(route('product.create.history'), [
            "_token" => Session::token(),
            "added_product_id" => 99999911,
            "added_product_name" => 'qwert',
            "added_use_period" => 0,
            "added_unit_price" => 12,
            "added_quantity" => 1,
            "added_use_report" => 0,
            "added_report_name" => null,
            "added_operator" => 'DEFAULT',
            "added_owner_type" => 'HISTORY',
            "added_owner_id" => 9999991,
        ]);

        $positiveResponseWithValidation->assertSessionHasNoErrors();

        $positiveResponseWithValidation = $this->post(route('product.create.history'), [
            "_token" => Session::token(),
            "added_product_id" => 99999912,
            "added_product_name" => 'qwert',
            "added_use_period" => 1,
            "added_unit_price" => 0,
            "added_quantity" => 0,
            "added_use_report" => 1,
            "added_report_name" => 'tes',
            "added_operator" => 'DEFAULT',
            "added_owner_type" => 'HISTORY',
            "added_owner_id" => 9999991,
        ]);

        $positiveResponseWithValidation->assertSessionHasNoErrors();

        $positiveResponseWithValidation = $this->post(route('create.invoice'), [
            "_token" => Session::token(),
            "added_invoice_history_id" => 9999991,
            "added_invoice_profile_id" => 2,
            "added_invoice_number" => 123,
            "added_invoice_date" => '2020-01-01',
            "added_due_date" => '2020-01-03',
            "added_ref_number" => '123232',
            "added_term_of_payment" => 3,
        ]);

        /**
         * testing the response with asserting the session has no errors. (Positive Test Case)
         */

        $positiveResponseWithValidation->assertSessionHasNoErrors();

        /**
         * Create new history with Negative Attribute Value
         */

        $positiveResponseWithValidation = $this->post(route('create.invoice'), [
            "_token" => Session::token(),
            "added_invoice_history_id" => 9999991,
            "added_invoice_profile_id" => 2,
            "added_invoice_number" => 123,
            "added_invoice_date" => '202021-01-01',
            "added_due_date" => '2020123-01-01',
            "added_ref_number" => '123232',
            "added_term_of_payment" => 3,
        ]);

        /**
         * testing the response with asserting the session has errors. (Positive Test Case)
         */

        $positiveResponseWithValidation->assertSessionHasErrors();

        $positiveResponseWithValidation = $this->post(route('update.invoice'), [
            "_token" => Session::token(),
            "edited_invoice_history_id" => 9999991,
            "edited_invoice_number" => 52323,
            "edited_invoice_date" => '2020-01-01',
            "edited_due_date" => '2020-01-05',
            "edited_term_of_payment" => 4,
            "edited_ref_number" => '2323',
        ]);

        /**
         * testing the response with asserting the session has no errors. (Positive Test Case)
         */

        $positiveResponseWithValidation->assertSessionHasNoErrors();

        /**
         * update history with Negative Attribute Value
         */

        $positiveResponseWithValidation = $this->post(route('update.invoice'), [
            "_token" => Session::token(),
            "edited_invoice_history_id" => 9999991,
            "edited_invoice_number" => 52323,
            "edited_invoice_date" => '2022320-01-01',
            "edited_due_date" => '20asdsa20-01-05',
            "edited_term_of_payment" => 12,
            "edited_ref_number" => '2323',
        ]);

        /**
         * testing the response with asserting the session has errors. (Positive Test Case)
         */

        $positiveResponseWithValidation->assertSessionHasErrors();
        
        /**
         * testing download all invoices (Positive Test Case)
         */

        $positiveResponseWithValidation = $this->post(route('download.all.invoices'), [
            "_token" => Session::token(),
            "added_invoice_month" => 11,
        ]);

        /**
         * testing the response with asserting the session has no errors. (Positive Test Case)
         */

        $positiveResponseWithValidation->assertSessionHasNoErrors();

        /**
         * testing download all invoices (Negative Test Case)
         */

        $positiveResponseWithValidation = $this->post(route('download.all.invoices'), [
            "_token" => Session::token(),
            "added_invoice_month" => 2,
        ]);

        /**
         * testing the response with asserting the session has errors. (Negative Test Case)
         */

        $positiveResponseWithValidation->assertSessionHasErrors();
        
        /** 
         * Check Detail
         */

        $response = $this->get('invoice/history/2');
        $response->assertStatus(200);

        $response = $this->get('invoice/edit/10000030');
        $response->assertStatus(200);

        $response = $this->get('invoice/lock/9999991');
        $response->assertStatus(302);

        $response = $this->get('invoice/preview/10000030');
        $response->assertStatus(200);

        $response = $this->get('invoice/download/10000030');
        $response->assertStatus(200);

        $response = $this->get('invoice/copy/10000030');
        $response->assertStatus(302);

        $response = $this->get('invoice/revise/10000030');
        $response->assertStatus(302);

        /**
         * Check the delete history is done (Positive Test Case)
         */

        $response = $this->get('invoice/delete/9999991');
        $response->assertStatus(302);

        $deleteProduct1 = InvoiceProduct::where('PRODUCT_ID', 99999911)->delete();
        $deleteProduct2 = InvoiceProduct::where('PRODUCT_ID', 99999912)->delete();
        $deleteUsername = User::where('ADMIN_USERNAME', 'username_21')->delete();
        $this->assertEquals(1, $deleteUsername);
    }
}
