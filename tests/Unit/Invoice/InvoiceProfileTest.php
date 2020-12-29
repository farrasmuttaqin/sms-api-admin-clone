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

class InvoiceProfileTest extends TestCase
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
     * Test Visit Profile Page
     *
     * @return  void
     */
    public function test_visit_profile_page()
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
     * insert & delete invoice profile unit test example.
     *
     * @return void
     */
    public function test_profile_crud()
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
         * Create new profile with Positive Attribute Value (UI Test)
         */

        $positiveResponseWithValidation = $this->post(route('profile.create'), [
            "_token" => Session::token(),
            "added_profile_id" => 9999991,
            "added_client" => 99997,
            "added_payment_detail" => 9999992,
            "added_auto_generate" => 0,
            "added_approved_name" => 'tes',
            "added_approved_position" => 'tes',
        ]);

        /**
         * testing the response with asserting the session has no errors. (Positive Test Case)
         */

        $positiveResponseWithValidation->assertSessionHasNoErrors();

        /**
         * Create new profile with Negative Attribute Value
         */

        $positiveResponseWithValidation = $this->post(route('profile.create'), [
            "_token" => Session::token(),
            "added_profile_id" => 9999991,
            "added_client" => 20,
            "added_payment_detail" => 200,
            "added_auto_generate" => 'tes',
            "added_approved_name" => 'tes',
            "added_approved_position" => 'tes',
        ]);

        /**
         * testing the response with asserting the session has errors. (Positive Test Case)
         */

        $positiveResponseWithValidation->assertSessionHasErrors();

        /**
         * Update profile with Positive then Negative Attribute Value (UI Test)
         */

        $positiveResponseWithValidation = $this->post(route('profile.update'), [
            "_token" => Session::token(),
            "edited_profile_id" => 9999991,
            "edited_payment_detail" => 9999992,
            "edited_auto_generate" => 0,
            "edited_approved_name" => 'BCA_ACC',
            "edited_approved_position" => '1232322',
        ]);
        $positiveResponseWithValidation->assertSessionHasNoErrors();
        
        $positiveResponseWithValidation = $this->post(route('profile.update'), [
            "_token" => Session::token(),
            "edited_profile_id" => 9999991,
            "edited_payment_detail" => 200,
            "edited_auto_generate" => 'tes',
            "edited_approved_name" => 'BCA_ACC',
            "edited_approved_position" => '1232322',
        ]);
        $positiveResponseWithValidation->assertSessionHasErrors();

        /**
         * Store product with Positive then Negative Attribute Value (UI Test)
         */

        $positiveResponseWithValidation = $this->post(route('product.create'), [
            "_token" => Session::token(),
            "added_product_id" => 9999991,
            "added_product_name" => 'qwert',
            "added_use_period" => 0,
            "added_unit_price" => 12,
            "added_quantity" => 1,
            "added_use_report" => 0,
            "added_report_name" => null,
            "added_operator" => 'DEFAULT',
            "added_owner_type" => 'PROFILE',
            "added_owner_id" => 6,
        ]);

        $positiveResponseWithValidation->assertSessionHasNoErrors();
        
        $positiveResponseWithValidation = $this->post(route('product.create'), [
            "_token" => Session::token(),
            "added_product_id" => 9999991,
            "added_product_name" => 'qwert',
            "added_use_period" => 'tes',
            "added_unit_price" => 12,
            "added_quantity" => 1,
            "added_use_report" => 0,
            "added_report_name" => null,
            "added_operator" => 'DEFAULT',
            "added_owner_type" => 'PROFILE',
            "added_owner_id" => 6,
        ]);
        $positiveResponseWithValidation->assertSessionHasErrors();

        /**
         * Update product with Positive then Negative Attribute Value (UI Test)
         */

        $positiveResponseWithValidation = $this->post(route('product.update'), [
            "_token" => Session::token(),
            "edited_product_id" => 9999991,
            "edited_product_name" => 'qwert',
            "edited_use_period" => 0,
            "edited_unit_price" => 12,
            "edited_quantity" => 2,
            "edited_use_report" => 0,
            "edited_report_name" => null,
            "edited_operator" => 'DEFAULT',
            "edited_owner_type" => 'PROFILE',
            "edited_owner_id" => 6,
        ]);

        $positiveResponseWithValidation->assertSessionHasNoErrors();
        
        $positiveResponseWithValidation = $this->post(route('product.update'), [
            "_token" => Session::token(),
            "edited_product_id" => 9999991,
            "edited_product_name" => 'qwert',
            "edited_use_period" => 'tes',
            "edited_unit_price" => 12,
            "edited_quantity" => 2,
            "edited_use_report" => 0,
            "edited_report_name" => null,
            "edited_operator" => 'DEFAULT',
            "edited_owner_type" => 'PROFILE',
            "edited_owner_id" => 6,
        ]);
        $positiveResponseWithValidation->assertSessionHasErrors();

        /**
         * Store product history with Positive then Negative Attribute Value (UI Test)
         */

        $positiveResponseWithValidation = $this->post(route('product.create.history'), [
            "_token" => Session::token(),
            "added_product_id" => 9999991,
            "added_product_name" => 'qwert',
            "added_use_period" => 0,
            "added_unit_price" => 12,
            "added_quantity" => 1,
            "added_use_report" => 0,
            "added_report_name" => null,
            "added_operator" => 'DEFAULT',
            "added_owner_type" => 'HISTORY',
            "added_owner_id" => 47,
        ]);

        $positiveResponseWithValidation->assertSessionHasNoErrors();
        
        $positiveResponseWithValidation = $this->post(route('product.create.history'), [
            "_token" => Session::token(),
            "added_product_id" => 9999991,
            "added_product_name" => 'qwert',
            "added_use_period" => 'tes',
            "added_unit_price" => 12,
            "added_quantity" => 1,
            "added_use_report" => 0,
            "added_report_name" => null,
            "added_operator" => 'DEFAULT',
            "added_owner_type" => 'HISTORY',
            "added_owner_id" => 47,
        ]);
        $positiveResponseWithValidation->assertSessionHasErrors();

        /**
         * Update product with Positive then Negative Attribute Value (UI Test)
         */

        $positiveResponseWithValidation = $this->post(route('product.update.history'), [
            "_token" => Session::token(),
            "edited_product_id" => 9999991,
            "edited_product_name" => 'qwert',
            "edited_use_period" => 0,
            "edited_unit_price" => 12,
            "edited_quantity" => 2,
            "edited_use_report" => 0,
            "edited_report_name" => null,
            "edited_operator" => 'DEFAULT',
            "edited_owner_type" => 'HISTORY',
            "edited_owner_id" => 47,
        ]);

        $positiveResponseWithValidation->assertSessionHasNoErrors();
        
        $positiveResponseWithValidation = $this->post(route('product.update.history'), [
            "_token" => Session::token(),
            "edited_product_id" => 9999991,
            "edited_product_name" => 'qwert',
            "edited_use_period" => 'tes',
            "edited_unit_price" => 12,
            "edited_quantity" => 2,
            "edited_use_report" => 0,
            "edited_report_name" => null,
            "edited_operator" => 'DEFAULT',
            "edited_owner_type" => 'HISTORY',
            "edited_owner_id" => 47,
        ]);
        $positiveResponseWithValidation->assertSessionHasErrors();
        

        /**
         * Check invoice profile detail (Positive Test Case)
         */

        $response = $this->get('invoice/profile/9999991');
        $response->assertStatus(200);

        /**
         * Delete product detail (Positive Test Case)
         */

        $response = $this->get('invoice/product/9999991/6');
        $response->assertStatus(302);

        $response = $this->get('invoice/product_history/9999991/47');
        $response->assertStatus(302);
        
        $deleteProfile = InvoiceProfile::where('INVOICE_PROFILE_ID', 9999991)->delete();
        $deleteUsername = User::where('ADMIN_USERNAME', 'username_21')->delete();
        $this->assertEquals(1, $deleteUsername);
    }
}
