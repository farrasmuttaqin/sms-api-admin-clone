<?php
/**
 * Copyright(c) 2020 1rstWAP. All rights reserved.
 */


/**
 * Directory configuration
 */
define('SMSAPIADMIN_CONFIG_DIR',                dirname(__FILE__).'/');
define('SMSAPIADMIN_BASE_DIR',                  dirname(SMSAPIADMIN_CONFIG_DIR).'/');
define('SMSAPIADMIN_ARCHIEVE_EXCEL_REPORT',     dirname(__DIR__, 2).'/public/archive/reports/');
define('BILLING_QUERY_HISTORY_DIR',             dirname(__DIR__, 2).'/public/archive/reports/history/');
define('SMSAPIADMIN_INVOICE_DIR',               SMSAPIADMIN_BASE_DIR.'archive/invoices/');


/**
 * Database Configuration
 */

define('DB_SMS_API_V2',         'SMS_API_V2_NEW');
define('DB_BILL_U_MESSAGE',     'BILL_U_MESSAGE_NEW');
define('DB_BILL_PRICELIST',     'BILL_PRICELIST_NEW');
define('DB_INVOICE',            'SMS_API_V2_NEW');


/**
 * Utility Configuration
 */
define('SMSAPIADMIN_SERVER_TIMEZONE',            'UTC');
define('REPORT_PER_BATCH_SIZE',                  100000);

/**
 * Invoice Setting
 */
define('SUMMARY_USER_API_CELL', 'B2');
define('SUMMARY_TOTAL_SMS_CHARGED_CELL', 'B8');
define('SUMMARY_TOTAL_PRICE_CELL', 'B9');
