<?php

if (!class_exists('msPaymentInterface')) {
	require_once dirname(dirname(dirname(__FILE__))) . '/model/minishop2/mspaymenthandler.class.php';
}

class Qiwi extends msPaymentHandler implements msPaymentInterface {

	function __construct(xPDOObject $object, $config = array()) {
		parent::__construct($object, $config);

		$siteUrl = $this->modx->getOption('site_url');
		$assetsUrl = $this->modx->getOption('assets_url') . 'components/minishop2/';

		$this->config = array_merge(array(
			'mode' => $this->modx->getOption('ms2_mspqiwi_mode', null, 'SOAP'),
			'paymentUrl' => $siteUrl . substr($assetsUrl, 1) . 'payment/qiwi.php',
			'checkoutUrl' => $this->modx->getOption('ms2_mspqiwi_url', null, 'https://w.qiwi.com/order/external/create.action'),
			'shopId' => $this->modx->getOption('ms2_mspqiwi_shopId', null, ''),
			'apiId' => $this->modx->getOption('ms2_mspqiwi_apiId', null, ''),
			'apiKey' => $this->modx->getOption('ms2_mspqiwi_apiKey', null, ''),
			'lifetime' => $this->modx->getOption('ms2_mspqiwi_lifetime', null, 24),
			//'check_agt' => $this->modx->getOption('ms2_mspqiwi_check_agt', null, false),
			'comment' => $this->modx->getOption('ms2_mspqiwi_comment', null, 'New Order'),
			'successId' => $this->modx->getOption('ms2_mspqiwi_successId', null, ''),
			'failureId' => $this->modx->getOption('ms2_mspqiwi_failureId', null, ''),
			'currency' => $this->modx->getOption('ms2_mspqiwi_currency', null, 'RUB'),
		), $config);
		// For compatibility with old versions
		if (empty($this->config['apiId'])) {
			$this->config['apiId'] = $this->config['shopId'];
		}
		if (empty($this->config['apiKey'])) {
			$this->config['apiKey'] = $this->modx->getOption('ms2_mspqiwi_shopKey');
		}
	}


	/**
	 * @param msOrder $order
	 *
	 * @return array|string
	 */
	public function send(msOrder $order) {
		if ($order->get('status') > 1) {
			return $this->error('ms2_err_status_wrong');
		}
		$http_query = $this->getPaymentLink($order);

		return $this->success('', array('redirect' => $http_query));
	}


	/**
	 * @param msOrder $order
	 *
	 * @return string
	 */
	public function getPaymentLink(msOrder $order) {
		if ($pdo = $this->modx->getService('pdoTools')) {
			$comment = $pdo->getChunk('@INLINE ' . $this->config['comment'], $order->toArray());
		}
		else {
			$comment = $this->config['comment'];
		}
		$request = array(
			'txn_id' => $order->get('id'),
			'from' => $this->config['shopId'],
			//'to' => '',
			'summ' => $order->get('cost'),
			'com' => $comment,
			'lifetime' => $this->config['lifetime'],
			'check_agt' => $this->config['check_agt'],
			'currency' => $this->config['currency'],
			'successUrl' => $this->config['paymentUrl'] . '?action=success',
			'failUrl' => $this->config['paymentUrl'] . '?action=failure',
		);
		$link = $this->config['checkoutUrl'] . '?' . http_build_query($request);

		return $link;
	}


	/**
	 * @param msOrder $order
	 * @param int $status
	 *
	 * @return bool
	 */
	public function receive(msOrder $order, $status = 0) {
		if (!empty($status)) {
			$this->modx->context->key = 'mgr';
			$response = $this->ms2->changeOrderStatus($order->get('id'), $status);
			if ($response !== true) {
				$this->modx->log(modX::LOG_LEVEL_ERROR, "[mspQiwi] Error on change status of order #{$order->num} to \"{$status}\": {$response}");
			}

			return $response;
		}

		return false;
	}


	/**
	 * Process response from service
	 *
	 * @return array/boolean
	 */
	public function processResult() {
		if (strtoupper($this->config['mode'] == 'SOAP')) {
			$properties = array(
				'classmap' => array(
					'tns:updateBill' => 'qiwiParam',
					'tns:updateBillResponse' => 'qiwiResponse',
				),
			);
			if (!class_exists('qiwiSOAPServer')) {
				require dirname(__FILE__) . '/lib/qiwi/soapserver.class.php';
			}
			$wsdl = MODX_CORE_PATH . 'components/minishop2/custom/payment/lib/qiwi/IShopClientWS.wsdl';
			$SOAP = new SoapServer($wsdl, $properties);
			$SOAP->setClass('qiwiSOAPServer', $this->config['apiId'], $this->config['apiKey']);
			$SOAP->handle();
		}
		else {
			if (!class_exists('qiwiRESTServer')) {
				require dirname(__FILE__) . '/lib/qiwi/restserver.class.php';
			}
			$REST = new qiwiRESTServer($this->modx, $this->config);
			$REST->handle($_REQUEST);
		}
	}


	/**
	 * @param $code
	 *
	 * @return string
	 */
	function responseCode($code) {
		$codes = array(
			0 => null,
			13 => 'Сервер занят, повторите запрос позже',
			150 => 'Ошибка авторизации (неверный логин/пароль)',
			210 => 'Счет не найден',
			215 => 'Счет с таким txn-id уже существует',
			241 => 'Сумма слишком мала',
			242 => 'Превышена максимальная сумма платежа – 15 000р.',
			278 => 'Превышение максимального интервала получения списка счетов',
			298 => 'Агента не существует в системе',
			300 => 'Данные магазина и платёжной системы не совпадают',
			330 => 'Ошибка шифрования',
			370 => 'Превышено максимальное кол-во одновременно выполняемых запросов',
		);

		return isset($codes[$code])
			? $codes[$code]
			: 'Неизвестный код';
	}

}