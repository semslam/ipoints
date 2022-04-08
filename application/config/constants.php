<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code


// ============== APP CONSTANTS ==========
define('IPOINTS_PER_UNIT_COST', 1.20);
define("BASE_URL", SITE_URL);
define("BACKOFFICE_HTML_PATH", BASE_URL . "/assets");

//============= MESSAGE TYPE  =========
define('MESSAGE_SMS', 'Sms');
define('MESSAGE_EMAIL', 'Email');
//============= MESSAGE TEMPLATE ACTION =========
define('REGISTER', 'register');
define('FORGOT_PASSWORD', 'forgot-password');
define('AUTO_USER', 'auto-user-otp');
define('RE_GENERATE_OTP', 're-generate-otp');
define('TRANSFER_CREDIT', 'transfer-credit');
define('TRANSFER_DEBIT', 'transfer-debit');
define('SMS_DEBIT', 'sms-debit');
define('IPOINT_OVERDRAFT', 'ipoint-overdraft');
define('MERCHANT_OFFLINE_PAYMENT', 'merchat-offline-payment-request');
define('PRODUCT_SERVICES_SUBSCRIPTION', 'product-subscription');
define('PRODUCT_SUBSCRIPTION_REPORT', 'product-subscription-report');
define('OFFLINE_PAYMENT_IPOINT_PURCHASE_REQUEST', 'offline-payment-ipoint-purchase-request');
define('OFFLINE_PAYMENT_PRODUCT_PURCHASE', 'offline-payment-product-purchase');
define('OFFLINE_PAYMENT_WALLET_FUND', 'offline-payment-wallet-fund');
define('OFFLINE_PAYMENT_REQUEST_VOID', 'offline-payment-request-void');
define('WITHDRAW_REQUEST', 'withdraw-request');
define('WITHDRAW_APPROVED', 'withdraw-approved');
define('WITHDRAW_CANCEL_BY_ADMIN', 'withdraw-cancel-by-admin');
define('WITHDRAW_CANCEL', 'withdraw-cancel');
define('IPIN_GENERATOR', 'ipin-generator');
define('BULK_TRANSFER', 'bulk-transfer');
define('ONLINE_TOPUP_WALLET_SUCCESS', 'online-topup-wallet-success');
define('ONLINE_TOPUP_WALLET_FAILED', 'online-topup-wallet-failed');
define('NEW_WIP_TRANSACTION', 'new-wip-transaction');// bulk transfer with user create
define('OLD_WIP_TRANSACTION', 'old-wip-transaction');// bulk transfer with no user create
define('NEW_WIP_TRANSACTION_ISAVING', 'new-wip-transaction-isaving');// bulk transfer with user create
define('OLD_WIP_TRANSACTION_ISAVING', 'old-wip-transaction-isaving');// bulk transfer with no user create
define('TRANSFER_CREDIT_NEW_USER', 'transfer-credit-new-user');// bulk transfer with no user create
define('CUMULATIVE_ISAVINGS_REPORT', 'cumulative-isavings-report');// bulk transfer with no user create
define('ESPI_PROCESS_ERROR_REPORT', 'espi-process-error-report');// bulk transfer with no user create


//============= LIST OF WALLETS  =========
define('I_POINT', 'iPoints');
define('I_HEALTH', 'iHealth');
define('I_LIFE', 'iLife');
define('I_SAVINGS', 'iSavings');
define('I_INSURANCE', 'iInsurance');
define('I_SUBSCRIPTION', 'Subscription');
define('I_INCOME', 'iIncome');
define('I_PENSION', 'iPension');
define('I_PIN', 'iPin');// system iPin wallet
define('I_WITHDRAWER', 'iWithdrawer');// system iWithdrawer wallet
define('I_SALES_COMMISSION', 'SalesCommission');//system iPoint sales commission charge
define('I_SAVINGS_EARNING', 'iSavingsCharge');// system iSavings commission charge
define('I_INSURANCE_EARNING', 'iInsuranceCharge');//system iInsurance commission charge
define('I_PENSION_EARNING', 'iPensionCharge');// system iPension commission charge

//============== point charges ============

define("IPOINT_UNIT_PRICE_KEY", 'iPoint_unit_price'); //seconds
define("ISAVINGS_CHARGES_KEY", 'iSavings_admin_charge'); //seconds
define("IINSURANCE_CHARGES_KEY", 'iInsurance_admin_charge'); //seconds
define("IPENSION_CHARGES_KEY", 'iPension_admin_charge');
define("ANNUAL_CHARGES_KEY", 'user_annual_subscription');

//============= LIST OF GROUPS  =========
define('ADMINISTRATOR', 'Administrator');
define('CUSTOMER_SERVICE', 'Customer Service');//Customer Service
define('MERCHANT', 'Merchant');
define('SUBSCRIBER', 'Subscriber');
define('UNDERWRITER', 'Underwriter');
define('PARTNER', 'Partner');
define('VENDOR', 'Vendor');

// ============ User constants ============
define('USER_STATUS_ACTIVE', 1);

define("OTP_EXPIRY_PERIOD", 1440); //seconds
define("ACCESS_TOKEN_EXPIRY_PERIOD", 86400); //seconds
define("ACCESS_TOKEN_NON_EXPIRY_PERIOD", 0);

define("USER_SUBSCRIPTION_BENCHMARK", 75);


// ============ Report Subscribers Type ============
define('CUMULATIVE', 'Cumulative');
define('SALES', 'Sales');
define('PRODUCT_SUBSCRIPTION', 'ProductSubscription');
define('WITHDRAWER_CANCELLATION', 'WithdrawerCancellation');
define('WITHDRAWER_REQUEST', 'WithdrawerRequest');
define('OFFLINE_PAYMENT_REQUEST', 'OfflinePaymentRequest');

// ============ Report Subscribers Dispatch Type ============
define('GROUP', 'group');
define('ALL', 'all');
define('INDIVIDUAL', 'individual');

// ============ Report Subscribers Frequency Type ============
define('MONTHLY', 'monthly');
define('DAILY', 'daily');
define('WEEKLY', 'weekly');
define('YEARLY', 'yearly');


//==============List Of Banks =====================
define('BANKS', [
'Access Bank',
'Citibank',
'Ecobank',
'Fidelity Bank',
'First Bank',
'First City Monument Bank (FCMB)',
'Guaranty Trust Bank (GTB)',
'Heritage Bank',
'Keystone Bank',
'Skye Bank',
'Stanbic IBTC Bank',
'Standard Chartered',
'Sterling Bank',
'Union Bank',
'United Bank for Africa (UBA)',
'Unity Bank',
'Wema Bank',
'Zenith Bank'
]);





//Message encryption code

//define('SECRET', 'BestVisionBlogPortfolioWebSiteCo');
define('SECRET', 'c9f5ec3e4f4d8179ee0a94dfe323264c');

// reCAPTCHA keys
define('RE_CAPTCHA_SITE_KEY', '6LdrRqkUAAAAAPQKGpIw7A83IQXjjWxyeGxYaE9m');
define('RE_CAPTCHA_SECRET_KEY', '6LdrRqkUAAAAAGdfhEkSnIs2E5qHslVTTxS77ypF');