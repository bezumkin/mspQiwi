<?php

if (!isset($modx)) {
	define('MODX_API_MODE', true);
	require dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/index.php';

	$modx->getService('error', 'error.modError');
}

$modx->error->message = null;
/** @var miniShop2 $miniShop2 */
$miniShop2 = $modx->getService('minishop2');
$miniShop2->loadCustomClasses('payment');
if (!class_exists('Qiwi')) {
	exit('Error: could not load payment class "Qiwi".');
}

/** @var msOrder $order */
$order = $modx->newObject('msOrder');
/** @var msPaymentInterface|Qiwi $handler */
$handler = new Qiwi($order);
switch ($_GET['action']) {
	//generate Qiwi request and redirect to Qiwi
	case 'continue':
		if (!empty($_GET['msorder'])) {
			if ($order = $modx->getObject('msOrder', $_GET['msorder'])) {
				$response = $handler->send($order);
				$modx->sendRedirect($response);
			}
		}
		break;
	//Going to Qiwi class to process payment
	case 'result':
		$handler->processResult();
		break;

	case 'success':
		$url = $modx->makeUrl($handler->config['successId'], '', array('result' => 'success'), 'full');
		$modx->sendRedirect($url);
		break;

	case 'failure':
		$url = $modx->makeUrl($handler->config['failureId'], '', array('result' => 'failure'), 'full');
		$modx->sendRedirect($url);
		break;
}




