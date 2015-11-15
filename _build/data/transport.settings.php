<?php

$settings = array();


$tmp = array(
	/*
	'mode' => array(
		'xtype' => 'textfield',
		'value' => 'SOAP',
	),
	*/
	'url' => array(
		'xtype' => 'textfield',
		'value' => 'https://w.qiwi.com/order/external/create.action',
	),

	'shopId' => array(
		'xtype' => 'textfield',
		'value' => '',
	),
	'apiId' => array(
		'xtype' => 'textfield',
		'value' => '',
	),
	'apiKey' => array(
		'xtype' => 'text-password',
		'value' => '',
	),
	/*
	'restKey' => array(
		'xtype' => 'text-password',
		'value' => '',
	),
	*/
	/*
	'shopKey' => array(
		'xtype' => 'text-password',
		'value' => '',
	),
	*/
	'currency' => array(
		'xtype' => 'textfield',
		'value' => 'RUB',
	),
	'lifetime' => array(
		'xtype' => 'textfield',
		'value' => 24,
	),
	/*
	'check_agt' => array(
		'xtype' => 'combo-boolean',
		'value' => false,
	),
	*/
	'comment' => array(
		'xtype' => 'textfield',
		'value' => 'Оплата заказа [[+num]]',
	),

	'successId' => array(
		'xtype' => 'numberfield',
		'value' => '',
	),
	'failureId' => array(
		'xtype' => 'numberfield',
		'value' => '',
	),

);

foreach ($tmp as $k => $v) {
	/* @var modSystemSetting $setting */
	$setting = $modx->newObject('modSystemSetting');
	$setting->fromArray(array_merge(
		array(
			'key' => 'ms2_mspqiwi_' . $k,
			'namespace' => 'minishop2',
			'area' => 'ms2_payment',
		), $v
	), '', true, true);

	$settings[] = $setting;
}

unset($tmp);
return $settings;
