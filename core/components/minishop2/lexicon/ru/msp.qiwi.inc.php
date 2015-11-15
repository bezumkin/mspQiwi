<?php

$_lang['area_mspqiwi'] = "Qiwi";

$_lang['setting_ms2_mspqiwi_url'] = 'URL сервиса';
$_lang['setting_ms2_mspqiwi_url_desc'] = 'Адрес для выставления счётов на сервере Qiwi. На него будет отправлен пользователь для проведения платежа.';

$_lang['setting_ms2_mspqiwi_shopId'] = 'ID магазина';
$_lang['setting_ms2_mspqiwi_shopId_desc'] = 'ID магазина в системе Qiwi, который выставляет счета';
$_lang['setting_ms2_mspqiwi_apiId'] = 'API ID';
$_lang['setting_ms2_mspqiwi_apiId_desc'] = 'Идентификатор для доступа к системе Qiwi через API. Генерируется в личном кабинете Qiwi.';
$_lang['setting_ms2_mspqiwi_apiKey'] = 'API Key';
$_lang['setting_ms2_mspqiwi_apiKey_desc'] = 'Ключ для доступа к системе Qiwi через API. Генерируется в личном кабинете Qiwi.';
/*
$_lang['setting_ms2_mspqiwi_mode'] = 'Режим работы';
$_lang['setting_ms2_mspqiwi_mode_desc'] = 'Вы можете использовать устаревший SOAP или новый REST протоколы';
$_lang['setting_ms2_mspqiwi_check_agt'] = 'Проверка наличия клиента в Qiwi';
$_lang['setting_ms2_mspqiwi_check_agt_desc'] = 'Если флаг установлен в true, то при попытке создания счета незарегистрированному агенту будет возвращаться соответствующая ошибка. Если флаг установлен в false, то при попытке создания счета будет создан новый агент.';
*/
$_lang['setting_ms2_mspqiwi_lifetime'] = 'Время жизни счета';
$_lang['setting_ms2_mspqiwi_lifetime_desc'] = 'Время действия счета (в часах) от момента создания. По истечении этого времени оплата невозможна (счет будет отменен)';
$_lang['setting_ms2_mspqiwi_comment'] = 'Комментарий';
$_lang['setting_ms2_mspqiwi_comment_desc'] = 'Комментарий для платежа в Qiwi. Цепляется к выставленному счету внутри Qiwi';
$_lang['setting_ms2_mspqiwi_currency'] = 'Код валюты';
$_lang['setting_ms2_mspqiwi_currency_desc'] = 'Идентификатор валюты счета (Alpha-3 ISO 4217 код). Может использоваться любая валюта, предусмотренная договором с КИВИ.';

$_lang['setting_ms2_mspqiwi_successId'] = 'Страница успешной оплаты Qiwi';
$_lang['setting_ms2_mspqiwi_successId_desc'] = 'ID страницы куда направить пользователя после успешной оплаты покупки';
$_lang['setting_ms2_mspqiwi_failureId'] = 'Страница отказа от оплаты Qiwi';
$_lang['setting_ms2_mspqiwi_failureId_desc'] = 'ID страницы куда направить пользователя в случае отказа от оплаты покупки';