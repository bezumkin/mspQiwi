<?php

class qiwiRESTServer {
	public $modx;
	public $config = array();


	function __construct(modX $modx, array $config = array()) {
		$this->modx = $modx;
		$this->config = $config;
	}


	/**
	 * @param array $response
	 */
	public function handle(array $response) {
		/** @var Qiwi $handler */
		$handler = new Qiwi(new msOrder($this->modx));
		$id = str_replace('_TEST_', '', $response['bill_id']);

		/** @var msOrder $order */
		if ($order = $this->modx->getObject('msOrder', $id)) {
			if (!$bill = $this->checkBill($id)) {
				return;
			}
			// Wrong amount in bill
			if ($bill['amount'] != $order->get('cost')) {
				$code = 300;
			}
			// Wrong status
			elseif ($response['status'] != $bill['status']) {
				$code = 300;
			}
			// Success
			elseif ($response['status'] == 'paid') {
				$handler->receive($order, 2);
				$code = 0;
			}
			// Cancel
			elseif ($response['status'] == 'rejected') {
				$handler->receive($order, 4);
				$code = 0;
			}
			// Unknown
			else {
				$code = 300;
			}
		}
		else {
			$code = 210;
		}

		if ($code != 0) {
			$message = $handler->responseCode($code);
			$this->modx->log(modX::LOG_LEVEL_ERROR, "[mspQiwi] Could not process payment \"{$response['bill_id']}\": code \"{$code}\", message: \"{$message}\".");
			$this->modx->log(modX::LOG_LEVEL_INFO, "[mspQiwi] Response: " . print_r($response, true));
			if (!empty($bill)) {
				$this->modx->log(modX::LOG_LEVEL_INFO, "[mspQiwi] Bill: " . print_r($bill, true));
			}
		}
	}


	/**
	 * @param $id
	 *
	 * @return bool|mixed
	 */
	public function checkBill($id) {
		$response = $this->_request('bills/' . $id);
		if (!empty($response['response'])) {
			if ($response['response']['result_code'] != 0) {
				$response = $response['response'];
				$this->modx->log(modX::LOG_LEVEL_ERROR, "[mspQiwi] Error on request to Qiwi. Code: \"$response[result_code]\", message: \"$response[description]\"");
			}
			else {
				return $response['response']['bill'];
			}
		}

		return false;
	}


	/**
	 * @param $action
	 *
	 * @return bool|mixed
	 */
	protected function _request($action) {
		$base_url = 'https://w.qiwi.com/api/v2/prv/' . $this->config['shopId'] . '/';

		$ch = curl_init($base_url . $action);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Accept: text/json",
			"Content-Type: application/x-www-form-urlencoded; charset=utf-8",
		));
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, $this->config['apiId'] . ':' . $this->config['apiKey']);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($ch);
		curl_close($ch);
		if ($response) {
			return $this->modx->fromJSON($response);
		}

		return false;
	}

}