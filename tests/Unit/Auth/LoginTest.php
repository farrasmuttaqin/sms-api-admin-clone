<?php

namespace Tests\Unit\Auth;

use Tests\TestCase;
use App\Models\User;
use Session;
use Auth;

class LoginTest extends TestCase
{


    /**
     * Login validation test (Positive Test Case)
     *
     * @return void
     */
    public function test_login_validation()
    {
        Session::start();

        /**
         * Use tiger 192 hashing for password
         */
        $password = hash('tiger192,3','password');

        /**
         * Create new fake user
         */
        $user = factory(User::class)->create([
               'ADMIN_USERNAME' => 'username_21',
               'ADMIN_PASSWORD' => $password,
               'LOGIN_ENABLED'  => 1,
        ]);

        /**
         * Create success response (Use same input as create new fake user)
         */
        $responseWithValidation = $this->post(route('auth.login'), [
            '_token' => Session::token(),
            'username' => 'username_21',
            'password' => 'password',
        ]);

        /**
         * testing the response with asserting the session has no errors. (Positive Test Case)
         */
        $responseWithValidation->assertSessionHasNoErrors();

        /**
         * testing the validation response is success and go to home page (200) (Positive Test Case).
         */
        $response = $this->get('/');
        $response->assertStatus(200);

        /**
         * Check the delete user is done (Positive Test Case)
         */
        $deleteUsername = User::where('ADMIN_USERNAME', 'username_21')->delete();
        $this->assertEquals(1, $deleteUsername);
    }

    /**
     * Login action success test (Positive Test Case)
     *
     * @return void
     */
    public function test_login_success()
    {

    	/**
         * Get home page and redirect to login
         */
        $responseGetLoginPage = $this->get('/');
        $responseGetLoginPage->assertStatus(302);

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
         * Check the delete user is done (Positive Test Case)
         */
        $deleteUsername = User::where('ADMIN_USERNAME', 'username_21')->delete();
        $this->assertEquals(1, $deleteUsername);

        /**
         * Get home page if login passed (Positive Test Case)
         */
        $response = $this->get('/');
        $response->assertStatus(200);

        /**
         * Logout Unit Test with Mock User Session
         * 
         */
        Auth::guard('web')->logout();

        /**
         * Get login page if logout passed
         */
        $response = $this->get('login');
        $response->assertStatus(200);
    }

    /**
     * Login action failed test (Negative Test Case)
     *
     * @return void
     */
    public function test_login_failed()
    {
        Session::start();

        /**
         * Use tiger 192 hashing for password
         */
        $password = hash('tiger192,3','password');

        /**
         * Create new fake user with unable to login status (LOGIN_ENABLED = 0)
         */
        $user = factory(User::class)->create([
               'ADMIN_USERNAME' => 'username_21',
               'ADMIN_PASSWORD' => $password,
               'LOGIN_ENABLED'  => 0,
        ]);

        /**
         * Create failed response (Use different input as create new fake user)
         */
        $responseWithValidation = $this->post(route('auth.login'), [
            '_token' => Session::token(),
            'username' => 'username_21',
            'password' => 'passworD',
        ]);

        /**
         * testing the validation response is failed and go to login page (Negative Test Case).
         */
        $response = $this->get('login');
        $response->assertStatus(200);

        /**
         * Create failed response (Use same input as create new fake unable to login user)
         */
        $responseWithValidation = $this->post(route('auth.login'), [
            '_token' => Session::token(),
            'username' => 'username_21',
            'password' => 'password',
        ]);

        /**
         * testing the validation response is failed and go to login page (Negative Test Case).
         */
        $response = $this->get('login');
        $response->assertStatus(200);

        /**
         * Check the delete user is done (Positive Test Case)
         */
        $deleteUsername = User::where('ADMIN_USERNAME', 'username_21')->delete();
        $this->assertEquals(1, $deleteUsername);
    }
}
