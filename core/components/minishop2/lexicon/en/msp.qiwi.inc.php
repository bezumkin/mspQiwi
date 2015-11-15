<?php

$_lang['area_mspqiwi'] = "Qiwi";

$_lang['setting_ms2_mspqiwi_url'] = 'URL of service';
$_lang['setting_ms2_mspqiwi_url_desc'] = 'Billing address on the server of Qiwi. It will be sent to the user for payment.';

$_lang['setting_ms2_mspqiwi_shopId'] = 'Your store ID';
$_lang['setting_ms2_mspqiwi_shopId_desc'] = 'ID of the store in the Qiwi system, which issues invoices';
$_lang['setting_ms2_mspqiwi_apiId'] = 'API ID';
$_lang['setting_ms2_mspqiwi_apiId_desc'] = 'the ID to access the system via Qiwi API. Is generated on the Qiwi website.';
$_lang['setting_ms2_mspqiwi_apiKey'] = 'API Key';
$_lang['setting_ms2_mspqiwi_apiKey_desc'] = 'Key to access the API via the Qiwi system. Also generated on the Qiwi website.';
/*
$_lang['setting_ms2_mspqiwi_mode'] = 'Mode';
$_lang['setting_ms2_mspqiwi_mode_desc'] = 'You can use outdated SOAP or actual REST protocols';
$_lang['setting_ms2_mspqiwi_check_agt'] = 'Checking whether client Qiwi';
$_lang['setting_ms2_mspqiwi_check_agt_desc'] = 'If this flag is set to true, then when you try to create the account of an unregistered agent will return the appropriate error. If the flag is set to false, then when you try to create account it will create a new agent.';
*/
$_lang['setting_ms2_mspqiwi_lifetime'] = 'Lifetime of order';
$_lang['setting_ms2_mspqiwi_lifetime_desc'] = 'Duration of order (in hours) from the time of creation. After this time the payment is impossible (the invoice will be canceled)';
$_lang['setting_ms2_mspqiwi_comment'] = 'Comment';
$_lang['setting_ms2_mspqiwi_comment_desc'] = 'Comment for payment in Qiwi. Clings to the invoice within Qiwi';
$_lang['setting_ms2_mspqiwi_currency'] = 'Currency code';
$_lang['setting_ms2_mspqiwi_currency_desc'] = 'The code of the order currency (Alpha-3 ISO 4217 code). Can be used any currency, provided for in the contract with QIWI.';

$_lang['setting_ms2_mspqiwi_successId'] = 'Success page';
$_lang['setting_ms2_mspqiwi_successId_desc'] = 'Page id where to send the user on successful payment';
$_lang['setting_ms2_mspqiwi_failureId'] = 'Failure page';
$_lang['setting_ms2_mspqiwi_failureId_desc'] = 'Page id where to send the user on failure payment';