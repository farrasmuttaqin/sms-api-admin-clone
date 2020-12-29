<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();

Route::group(['namespace'=>'Auth'], function(){
	Route::post('logout', 'LoginController@logout')->name('auth.logout');
	Route::post('login', 'LoginController@checklogin')->name('auth.login');
});


Route::group(['middleware'=>'auth'], function(){

	Route::group(['namespace'=>'Client'], function(){
		Route::get('/', 'ClientController@index')->name('home');
		Route::post('client/create', 'ClientController@store')->name('client.create');
		Route::get('client/{CLIENT_ID}', 'ClientController@destroy')->name('client.delete');
		Route::post('client/edit', 'ClientController@update')->name('client.update');
		Route::get('client/change_status/{CLIENT_ID}', 'ClientController@changeArchived')->name('client.change.status');
	});

	Route::group(['namespace'=>'Cobrander'], function(){
		Route::get('cobrander', 'CobranderController@index')->name('cobrander');
		Route::post('cobrander/create', 'CobranderController@store')->name('cobrander.create');
		Route::get('cobrander/{COBRANDER_ID}', 'CobranderController@destroy')->name('cobrander.delete');

		// Route::post('cobrander/destroy', 'CobranderController@destroy')->name('cobrander.delete');

		Route::post('cobrander/edit', 'CobranderController@update')->name('cobrander.update');

		Route::get('agent', 'AgentController@index')->name('agent');
		Route::post('agent/create', 'AgentController@store')->name('agent.create');
		Route::get('agent/{AGENT_ID}', 'AgentController@destroy')->name('agent.delete');
		Route::post('agent/edit', 'AgentController@update')->name('agent.update');

		Route::post('all/operators', 'CobranderController@getAllOperators')->name('operator.all');
	});

	Route::group(['namespace'=>'User'], function(){
		Route::get('user', 'UserController@index')->name('user');
		Route::get('user/edit/{USER_ID}', 'UserController@detail_edit')->name('user.detail_edit');
		Route::get('user/detail/{USER_ID}', 'UserController@detail')->name('user.detail');

		Route::get('user/client/{CLIENT_ID}', 'UserController@user_detail')->name('client.user_detail');

		Route::post('user/create', 'UserController@store')->name('user.create');
		Route::get('user/{USER_ID}', 'UserController@destroy')->name('user.delete');
		Route::get('user/change/{USER_ID}/{ADAPT}', 'UserController@changeActiveStatus')->name('user.change_status');
		Route::post('user/edit', 'UserController@update')->name('user.update');
		Route::post('user/edit_password', 'UserController@updatePassword')->name('user.update.password');

		Route::post('download/user/billing', 'UserController@download_billing')->name('download.user.billing');

		Route::post('download/user/billing/all/reports', 'UserController@download_billing_reports')->name('download.billing.reports');

		/**
		 * User Detail CRUD
		 */

		Route::post('ip/create', 'IPController@store')->name('ip.create');
		Route::get('ip/{IP_ID}', 'IPController@destroy')->name('ip.delete');

		Route::post('vn/create', 'VNController@store')->name('vn.create');
		Route::get('vn/{VN_ID}', 'VNController@destroy')->name('vn.delete');
		Route::post('vn/edit', 'VNController@update')->name('vn.update');

		Route::post('sender/create', 'SenderController@store')->name('sender.create');
		Route::get('sender/{SENDER_ID}', 'SenderController@destroy')->name('sender.delete');
		Route::post('sender/edit', 'SenderController@update')->name('sender.update');

		Route::get('credit/{USER_ID}', 'CreditController@index')->name('credit.detail');
		Route::post('credit/top_up', 'CreditController@top_up')->name('credit.top_up');
		Route::post('credit/deduct', 'CreditController@deduct')->name('credit.deduct');
		Route::post('credit/update', 'CreditController@update')->name('credit.update');
		Route::post('credit/payment_acknowledgement', 'CreditController@update_payment_acknowledgement')->name('credit.payment_acknowledgement');
		

	});

	Route::group(['namespace'=>'Billing'], function(){
		Route::get('billing', 'BillingProfileController@index')->name('billing');
		Route::post('billing/create', 'BillingProfileController@store')->name('billing.create');
		Route::get('billing/{BILLING_ID}', 'BillingProfileController@destroy')->name('billing.delete');

		Route::get('find_billing_name/{BILLING_NAME}', 'BillingProfileController@findBillingName')->name('find.billing.name');
		Route::get('find_users/{BILLING_ID}', 'BillingProfileController@findBillingUsers')->name('find.users');
		Route::get('find_users_tg/{BILLING_TIERING_GROUP_ID}', 'TieringGroupController@findBillingUsersTieringGroup')->name('find.users_tg');
		Route::get('find_users_rg/{BILLING_REPORT_GROUP_ID}', 'ReportGroupController@findBillingUsersReportGroup')->name('find.users_rg');

		Route::get('find_tiering_settings/{BILLING_ID}', 'BillingProfileController@findTieringSettings')->name('find.tiering.settings');
		Route::get('find_operator_settings/{BILLING_ID}', 'BillingProfileController@findOperatorSettings')->name('find.operator.settings');
		Route::get('find_tiering_operator_settings/{BILLING_ID}', 'BillingProfileController@findTieringOperatorSettings')->name('find.tiering.operator.settings');

		Route::post('tiering_group', 'TieringGroupController@store')->name('tiering_group.create');
		Route::get('tiering_group/{BILLING_TIERING_GROUP_ID}', 'TieringGroupController@destroy')->name('tiering_group.delete');

		Route::post('report_group', 'ReportGroupController@store')->name('report_group.create');
		Route::get('report_group/{BILLING_REPORT_GROUP_ID}', 'ReportGroupController@destroy')->name('report_group.delete');

		Route::post('billing/update', 'BillingProfileController@update')->name('billing.update');
		Route::post('tiering_group/update', 'TieringGroupController@update')->name('tiering.group.update');
		Route::post('report_group/update', 'ReportGroupController@update')->name('report.group.update');
		
		/**
		 * Route for Generate Content Department Filter
		 */

		Route::get('filter', 'FilterController@index')->name('generate.sms.content.index');
		Route::post('filter/process', 'FilterController@process')->name('generate.sms.content');
		Route::get('filter/download/{reportName}', 'FilterController@download_filter_message')->name('download.filter');

	});

	Route::group(['namespace'=>'Invoice'], function(){
		Route::get('invoice', 'InvoiceHistoryController@index')->name('invoice');

		Route::get('invoice/history/{PROFILE_ID}', 'InvoiceHistoryController@index_history')->name('invoice.index.history');
		Route::get('invoice/profile/{PROFILE_ID}', 'InvoiceProfileController@index_profile')->name('invoice.index.profile');

		Route::post('invoice/bank/create', 'InvoiceBankController@store')->name('bank.create');
		Route::post('invoice/bank/update', 'InvoiceBankController@update')->name('bank.update');
		Route::post('invoice/setting/update', 'InvoiceBankController@update_setting')->name('setting.update');
		Route::get('invoice/bank/{BANK_ID}', 'InvoiceBankController@destroy')->name('bank.delete');

		Route::post('invoice/product/create', 'InvoiceProfileController@store_product')->name('product.create');
		Route::post('invoice/product/update', 'InvoiceProfileController@update_product')->name('product.update');
		Route::get('invoice/product/{PRODUCT_ID}/{INVOICE_PROFILE_ID}', 'InvoiceProfileController@destroy_product')->name('product.delete');

		Route::post('invoice/product_history/create', 'InvoiceProfileController@store_product_history')->name('product.create.history');
		Route::post('invoice/product_history/update', 'InvoiceProfileController@update_product_history')->name('product.update.history');
		Route::get('invoice/product_history/{PRODUCT_ID}/{HISTORY_ID}', 'InvoiceProfileController@destroy_product_history')->name('product.delete.history');

		Route::post('invoice/profile/create', 'InvoiceProfileController@store')->name('profile.create');
		Route::post('invoice/profile/update', 'InvoiceProfileController@update')->name('profile.update');

		Route::get('invoice/edit/{HISTORY_ID}', 'InvoiceHistoryController@index_edit')->name('invoice.index.edit');
		Route::get('invoice/lock/{HISTORY_ID}', 'InvoiceHistoryController@lock')->name('invoice.lock');
		Route::get('invoice/delete/{HISTORY_ID}', 'InvoiceHistoryController@destroy')->name('invoice.delete');

		Route::post('invoice/edit', 'InvoiceHistoryController@store')->name('create.invoice');
		Route::post('invoice/update', 'InvoiceHistoryController@update')->name('update.invoice');

		Route::get('invoice/preview/{HISTORY_ID}', 'InvoiceHistoryController@preview')->name('preview.invoice');

		Route::get('invoice/download/{HISTORY_ID}', 'InvoiceHistoryController@download')->name('download.invoice');
		Route::get('invoice/copy/{HISTORY_ID}', 'InvoiceHistoryController@copy')->name('copy.invoice');
		Route::get('invoice/revise/{HISTORY_ID}', 'InvoiceHistoryController@revise')->name('revise.invoice');

		Route::post('download_all_invoices', 'InvoiceHistoryController@download_all_invoices')->name('download.all.invoices');
	});

});

